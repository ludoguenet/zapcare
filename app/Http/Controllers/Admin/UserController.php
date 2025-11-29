<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Doctors\UpdateOfficeHours;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UpdateOfficeHours $updateOfficeHours
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('specialties')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $specialties = \App\Models\Specialty::all();
        
        // Load existing schedule data if user is a doctor
        $scheduleData = [];
        if ($user->is_doctor) {
            $officeHoursSchedules = $user->schedules()
                ->where('name', 'Office Hours')
                ->with('periods')
                ->get();
            
            // Process each schedule to build the complete schedule data
            foreach ($officeHoursSchedules as $schedule) {
                // Get days from frequency_config
                $frequencyConfig = $schedule->frequency_config ?? [];
                $days = $frequencyConfig['days'] ?? [];
                
                // Determine AM/PM availability from periods for this specific schedule
                $hasMorning = false;
                $hasAfternoon = false;
                
                foreach ($schedule->periods as $period) {
                    $startHour = (int) date('H', strtotime($period->start_time));
                    if ($startHour < 12) {
                        $hasMorning = true;
                    } else {
                        $hasAfternoon = true;
                    }
                }
                
                // Build schedule data structure for each day in this schedule
                foreach ($days as $day) {
                    // Initialize the day if it doesn't exist
                    if (!isset($scheduleData[$day])) {
                        $scheduleData[$day] = ['am' => false, 'pm' => false];
                    }
                    
                    // Set AM/PM based on this schedule's periods
                    if ($hasMorning) {
                        $scheduleData[$day]['am'] = true;
                    }
                    if ($hasAfternoon) {
                        $scheduleData[$day]['pm'] = true;
                    }
                }
            }
        }
        
        return view('admin.users.edit', compact('user', 'specialties', 'scheduleData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_doctor' => 'boolean',
            'specialties' => 'array',
            'specialties.*' => 'exists:specialties,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_doctor' => $request->has('is_doctor'),
        ]);

        if ($user->is_doctor) {
            if (isset($validated['specialties'])) {
                $user->specialties()->sync($validated['specialties']);
            } else {
                $user->specialties()->detach();
            }
            
            // Update schedule if provided
            if ($request->has('schedule')) {
                $this->updateOfficeHours->execute($user, $request->input('schedule', []));
            }
        } else {
            $user->specialties()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}

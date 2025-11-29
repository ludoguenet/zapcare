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
            $officeHoursSchedule = $user->schedules()
                ->where('name', 'Office Hours')
                ->with('periods')
                ->first();
            
            if ($officeHoursSchedule) {
                // Get days from frequency_config
                $frequencyConfig = $officeHoursSchedule->frequency_config ?? [];
                $days = $frequencyConfig['days'] ?? [];
                
                // Determine AM/PM availability from periods
                $hasMorning = false;
                $hasAfternoon = false;
                
                foreach ($officeHoursSchedule->periods as $period) {
                    $startHour = (int) date('H', strtotime($period->start_time));
                    if ($startHour < 12) {
                        $hasMorning = true;
                    } else {
                        $hasAfternoon = true;
                    }
                }
                
                // Build schedule data structure
                foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                    if (in_array($day, $days)) {
                        $scheduleData[$day] = [
                            'am' => $hasMorning,
                            'pm' => $hasAfternoon,
                        ];
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

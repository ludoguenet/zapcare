<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Doctors\CreateOfficeHours;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Zap\Facades\Zap;

class ScheduleController extends Controller
{
    public function __construct(
        private CreateOfficeHours $createOfficeHours
    ) {}

    /**
     * Display schedule management for a doctor.
     */
    public function show(User $doctor)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $doctor->load('schedules.periods');
        $schedules = $doctor->schedules()->with('periods')->get();

        return view('admin.schedules.show', compact('doctor', 'schedules'));
    }

    /**
     * Create office hours for a doctor.
     */
    public function createOfficeHours(Request $request, User $doctor)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $validated = $request->validate([
            'days' => 'required|array',
            'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'morning_start' => 'required|date_format:H:i',
            'morning_end' => 'required|date_format:H:i|after:morning_start',
            'afternoon_start' => 'required|date_format:H:i',
            'afternoon_end' => 'required|date_format:H:i|after:afternoon_start',
        ]);

        $this->createOfficeHours->execute(
            $doctor,
            $validated['days'],
            $validated['morning_start'],
            $validated['morning_end'],
            $validated['afternoon_start'],
            $validated['afternoon_end']
        );

        return back()->with('success', 'Office hours created successfully.');
    }

    /**
     * Create a blocked period.
     */
    public function createBlockedPeriod(Request $request, User $doctor)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Zap::for($doctor)
            ->named($validated['name'])
            ->blocked()
            ->from($validated['date'])
            ->addPeriod($validated['start_time'], $validated['end_time'])
            ->save();

        return back()->with('success', 'Blocked period created successfully.');
    }

    /**
     * Preview bookable slots for a doctor.
     */
    public function previewSlots(User $doctor, Request $request)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $date = $request->get('date', now()->format('Y-m-d'));
        $slots = $doctor->getBookableSlots($date, 60, 15);

        return view('admin.schedules.preview', compact('doctor', 'date', 'slots'));
    }
}

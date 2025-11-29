<?php

namespace App\Http\Controllers;

use App\Actions\Doctors\CreateAppointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(
        private CreateAppointment $createAppointment
    ) {}

    /**
     * Show the appointment booking form.
     */
    public function create(User $doctor, Request $request)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $date = $request->get('date', now()->format('Y-m-d'));
        $slots = $doctor->getBookableSlots($date, 60, 15);

        return view('appointments.create', compact('doctor', 'date', 'slots'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request, User $doctor)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'patient_name' => 'required|string|max:255',
        ]);

        // Check if slot is still available
        $slots = $doctor->getBookableSlots($validated['date'], 60, 15);
        $isAvailable = collect($slots)->first(function ($slot) use ($validated) {
            return $slot['start_time'] === $validated['start_time'] 
                && $slot['end_time'] === $validated['end_time']
                && $slot['is_available'] === true;
        });

        if (!$isAvailable) {
            return back()->withErrors(['slot' => 'This time slot is no longer available.'])->withInput();
        }

        try {
            $this->createAppointment->execute(
                $doctor,
                $validated['date'],
                $validated['start_time'],
                $validated['end_time'],
                $validated['patient_name'],
                Auth::id()
            );

            return redirect()->route('appointments.confirmation')
                ->with('success', 'Appointment booked successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to book appointment. Please try again.'])->withInput();
        }
    }

    /**
     * Show appointment confirmation.
     */
    public function confirmation()
    {
        return view('appointments.confirmation');
    }
}

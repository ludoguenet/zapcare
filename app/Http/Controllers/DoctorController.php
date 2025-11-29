<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of doctors.
     */
    public function index(Request $request)
    {
        $query = User::where('is_doctor', true)->with('specialties');

        // Filter by specialty if provided
        if ($request->has('specialty_id') && $request->specialty_id) {
            $query->whereHas('specialties', function ($q) use ($request) {
                $q->where('specialties.id', $request->specialty_id);
            });
        }

        $doctors = $query->paginate(12);
        $specialties = Specialty::all();

        return view('doctors.index', compact('doctors', 'specialties'));
    }

    /**
     * Display the specified doctor.
     */
    public function show(User $doctor)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $doctor->load('specialties');

        return view('doctors.show', compact('doctor'));
    }

    /**
     * Get available slots for a doctor on a specific date.
     */
    public function slots(User $doctor, Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        if (!$doctor->is_doctor) {
            return response()->json(['error' => 'Invalid doctor'], 404);
        }

        $date = $request->date;
        $slots = $doctor->getBookableSlots($date, 60, 15);

        return response()->json([
            'slots' => $slots,
            'date' => $date,
        ]);
    }

    /**
     * Display doctor profile with all schedule information.
     */
    public function profile(User $doctor)
    {
        if (!$doctor->is_doctor) {
            abort(404);
        }

        $doctor->load('specialties');
        
        // Load all schedules with periods
        $allSchedules = $doctor->schedules()
            ->with('periods')
            ->orderBy('start_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group schedules by type
        $appointments = $allSchedules->filter(function ($schedule) {
            return $schedule->schedule_type && $schedule->schedule_type->value === 'appointment';
        });

        $blocked = $allSchedules->filter(function ($schedule) {
            return $schedule->schedule_type && $schedule->schedule_type->value === 'blocked';
        });

        $buffer = $allSchedules->filter(function ($schedule) {
            return $schedule->schedule_type && $schedule->schedule_type->value === 'buffer';
        });

        $availability = $allSchedules->filter(function ($schedule) {
            return $schedule->schedule_type && 
                   $schedule->schedule_type->value === 'availability' && 
                   $schedule->name === 'Office Hours';
        });

        return view('doctors.profile', compact('doctor', 'appointments', 'blocked', 'buffer', 'availability'));
    }
}

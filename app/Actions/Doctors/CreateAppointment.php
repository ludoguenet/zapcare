<?php

namespace App\Actions\Doctors;

use App\Models\User;
use Carbon\Carbon;
use Zap\Facades\Zap;

class CreateAppointment
{
    /**
     * Create an appointment for a doctor.
     */
    public function execute(User $doctor, string $date, string $startTime, string $endTime, string $patientName, ?int $patientId = null): void
    {
        $metadata = [
            'patient_name' => $patientName,
        ];

        if ($patientId) {
            $metadata['patient_id'] = $patientId;
        }

        Zap::for($doctor)
            ->named('Appointment - ' . $patientName)
            ->appointment()
            ->from($date)
            ->addPeriod($startTime, $endTime)
            ->withMetadata($metadata)
            ->noOverlap()
            ->save();
    }
}

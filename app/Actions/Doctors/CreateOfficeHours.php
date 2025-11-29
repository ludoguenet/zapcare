<?php

namespace App\Actions\Doctors;

use App\Models\User;
use Zap\Facades\Zap;

class CreateOfficeHours
{
    /**
     * Create office hours for a doctor.
     */
    public function execute(User $doctor, array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'], string $morningStart = '09:00', string $morningEnd = '12:00', string $afternoonStart = '14:00', string $afternoonEnd = '17:00'): void
    {
        // Create availability schedule for office hours
        Zap::for($doctor)
            ->named('Office Hours')
            ->availability()
            ->from(now()->toDateString())
            ->weekly($days)
            ->addPeriod($morningStart, $morningEnd)
            ->addPeriod($afternoonStart, $afternoonEnd)
            ->save();
    }
}

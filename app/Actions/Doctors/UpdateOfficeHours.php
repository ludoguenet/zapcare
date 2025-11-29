<?php

namespace App\Actions\Doctors;

use App\Models\User;
use Zap\Facades\Zap;

class UpdateOfficeHours
{
    /**
     * Update office hours for a doctor.
     * This will delete existing "Office Hours" schedules and create a new one.
     */
    public function execute(User $doctor, array $scheduleData): void
    {
        // Delete existing "Office Hours" schedules
        $doctor->schedules()
            ->where('name', 'Office Hours')
            ->delete();

        // Collect days that have at least AM or PM selected
        $days = [];
        $hasAM = false;
        $hasPM = false;

        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $hasDayAM = isset($scheduleData[$day]['am']) && $scheduleData[$day]['am'];
            $hasDayPM = isset($scheduleData[$day]['pm']) && $scheduleData[$day]['pm'];

            if ($hasDayAM || $hasDayPM) {
                $days[] = $day;
                if ($hasDayAM) {
                    $hasAM = true;
                }
                if ($hasDayPM) {
                    $hasPM = true;
                }
            }
        }

        if (empty($days)) {
            return;
        }

        // Determine which periods to add based on AM/PM selections
        $periods = [];

        // Default times
        $morningStart = '09:00';
        $morningEnd = '12:00';
        $afternoonStart = '14:00';
        $afternoonEnd = '17:00';

        // Add morning period if AM is selected for any day
        if ($hasAM) {
            $periods[] = [$morningStart, $morningEnd];
        }

        // Add afternoon period if PM is selected for any day
        if ($hasPM) {
            $periods[] = [$afternoonStart, $afternoonEnd];
        }

        if (empty($periods)) {
            return;
        }

        // Create the schedule
        $zap = Zap::for($doctor)
            ->named('Office Hours')
            ->availability()
            ->from(now()->toDateString())
            ->weekly($days);

        foreach ($periods as $period) {
            $zap->addPeriod($period[0], $period[1]);
        }

        $zap->save();
    }
}

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

        // Default times
        $morningStart = '09:00';
        $morningEnd = '12:00';
        $afternoonStart = '14:00';
        $afternoonEnd = '17:00';

        // Group days by their period combinations (AM only, PM only, or both)
        $dayGroups = [
            'am_only' => [],
            'pm_only' => [],
            'both' => [],
        ];

        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $hasDayAM = isset($scheduleData[$day]['am']) && $scheduleData[$day]['am'];
            $hasDayPM = isset($scheduleData[$day]['pm']) && $scheduleData[$day]['pm'];

            if ($hasDayAM && $hasDayPM) {
                $dayGroups['both'][] = $day;
            } elseif ($hasDayAM) {
                $dayGroups['am_only'][] = $day;
            } elseif ($hasDayPM) {
                $dayGroups['pm_only'][] = $day;
            }
        }

        // Create schedules for each group
        $hasAnySchedule = false;
        foreach ($dayGroups as $groupType => $days) {
            if (empty($days)) {
                continue;
            }

            $hasAnySchedule = true;
            $zap = Zap::for($doctor)
                ->named('Office Hours')
                ->availability()
                ->from(now()->toDateString())
                ->weekly($days);

            // Add periods based on group type
            if ($groupType === 'am_only') {
                $zap->addPeriod($morningStart, $morningEnd);
            } elseif ($groupType === 'pm_only') {
                $zap->addPeriod($afternoonStart, $afternoonEnd);
            } else { // both
                $zap->addPeriod($morningStart, $morningEnd);
                $zap->addPeriod($afternoonStart, $afternoonEnd);
            }

            $zap->save();
        }

        // If no schedules were created, create an empty one to ensure doctor always has a schedule
        if (!$hasAnySchedule) {
            Zap::for($doctor)
                ->named('Office Hours')
                ->availability()
                ->from(now()->toDateString())
                ->weekly([])
                ->save();
        }
    }
}

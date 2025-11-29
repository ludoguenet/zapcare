<?php

namespace Tests\Feature;

use App\Actions\Doctors\UpdateOfficeHours;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOfficeHoursTest extends TestCase
{
    use RefreshDatabase;

    private UpdateOfficeHours $updateOfficeHours;

    protected function setUp(): void
    {
        parent::setUp();
        $this->updateOfficeHours = new UpdateOfficeHours();
    }

    public function test_updates_office_hours_with_mixed_am_pm_periods(): void
    {
        // This is the specific bug scenario from the user
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        $scheduleData = [
            'monday' => ['am' => '1', 'pm' => '1'],
            'tuesday' => ['am' => '1', 'pm' => '1'],
            'wednesday' => ['am' => '1'], // Only AM, no PM
            'thursday' => ['am' => '1', 'pm' => '1'],
        ];

        $this->updateOfficeHours->execute($doctor, $scheduleData);

        // Reload doctor with schedules
        $doctor->refresh();
        $schedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->with('periods')
            ->get();

        // Should create 2 schedules: one for days with both AM/PM, one for AM-only days
        $this->assertCount(2, $schedules);

        // Find the schedule for days with both AM and PM
        $bothSchedule = $schedules->first(function ($schedule) {
            $config = $schedule->frequency_config ?? [];
            $days = $config['days'] ?? [];
            return in_array('monday', $days) && in_array('tuesday', $days) && in_array('thursday', $days);
        });

        $this->assertNotNull($bothSchedule, 'Schedule for days with both AM and PM should exist');
        $this->assertCount(2, $bothSchedule->periods, 'Days with both AM/PM should have 2 periods');
        
        // Verify the periods are correct (morning and afternoon)
        $periodTimes = $bothSchedule->periods->map(function ($period) {
            return [$period->start_time, $period->end_time];
        })->toArray();
        
        $this->assertContains(['09:00:00', '12:00:00'], $periodTimes, 'Should have morning period');
        $this->assertContains(['14:00:00', '17:00:00'], $periodTimes, 'Should have afternoon period');

        // Find the schedule for AM-only days
        $amOnlySchedule = $schedules->first(function ($schedule) {
            $config = $schedule->frequency_config ?? [];
            $days = $config['days'] ?? [];
            return in_array('wednesday', $days) && count($days) === 1;
        });

        $this->assertNotNull($amOnlySchedule, 'Schedule for AM-only days should exist');
        $this->assertCount(1, $amOnlySchedule->periods, 'AM-only days should have only 1 period');
        
        // Verify it's the morning period
        $amPeriod = $amOnlySchedule->periods->first();
        $this->assertEquals('09:00:00', $amPeriod->start_time);
        $this->assertEquals('12:00:00', $amPeriod->end_time);
    }

    public function test_updates_office_hours_with_am_only_days(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        $scheduleData = [
            'monday' => ['am' => '1'],
            'tuesday' => ['am' => '1'],
        ];

        $this->updateOfficeHours->execute($doctor, $scheduleData);

        $doctor->refresh();
        $schedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->with('periods')
            ->get();

        $this->assertCount(1, $schedules);
        
        $schedule = $schedules->first();
        $this->assertCount(1, $schedule->periods);
        
        $period = $schedule->periods->first();
        $this->assertEquals('09:00:00', $period->start_time);
        $this->assertEquals('12:00:00', $period->end_time);
    }

    public function test_updates_office_hours_with_pm_only_days(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        $scheduleData = [
            'friday' => ['pm' => '1'],
            'saturday' => ['pm' => '1'],
        ];

        $this->updateOfficeHours->execute($doctor, $scheduleData);

        $doctor->refresh();
        $schedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->with('periods')
            ->get();

        $this->assertCount(1, $schedules);
        
        $schedule = $schedules->first();
        $this->assertCount(1, $schedule->periods);
        
        $period = $schedule->periods->first();
        $this->assertEquals('14:00:00', $period->start_time);
        $this->assertEquals('17:00:00', $period->end_time);
    }

    public function test_updates_office_hours_with_all_three_groups(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        $scheduleData = [
            'monday' => ['am' => '1', 'pm' => '1'],      // Both
            'tuesday' => ['am' => '1'],                   // AM only
            'wednesday' => ['pm' => '1'],                 // PM only
        ];

        $this->updateOfficeHours->execute($doctor, $scheduleData);

        $doctor->refresh();
        $schedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->with('periods')
            ->get();

        // Should create 3 schedules: one for each group
        $this->assertCount(3, $schedules);

        // Verify each schedule has the correct periods
        foreach ($schedules as $schedule) {
            $config = $schedule->frequency_config ?? [];
            $days = $config['days'] ?? [];
            
            if (in_array('monday', $days)) {
                // Both AM and PM
                $this->assertCount(2, $schedule->periods);
            } elseif (in_array('tuesday', $days)) {
                // AM only
                $this->assertCount(1, $schedule->periods);
                $this->assertEquals('09:00:00', $schedule->periods->first()->start_time);
            } elseif (in_array('wednesday', $days)) {
                // PM only
                $this->assertCount(1, $schedule->periods);
                $this->assertEquals('14:00:00', $schedule->periods->first()->start_time);
            }
        }
    }

    public function test_deletes_existing_office_hours_before_creating_new_ones(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        // Create initial office hours
        $initialData = [
            'monday' => ['am' => '1', 'pm' => '1'],
        ];
        $this->updateOfficeHours->execute($doctor, $initialData);

        $initialScheduleCount = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->count();

        $this->assertGreaterThan(0, $initialScheduleCount);

        // Update with new data
        $newData = [
            'tuesday' => ['am' => '1'],
        ];
        $this->updateOfficeHours->execute($doctor, $newData);

        $doctor->refresh();
        $newSchedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->get();

        // Should have deleted old schedules and created new ones
        $this->assertCount(1, $newSchedules);
        
        $schedule = $newSchedules->first();
        $config = $schedule->frequency_config ?? [];
        $days = $config['days'] ?? [];
        
        $this->assertContains('tuesday', $days);
        $this->assertNotContains('monday', $days);
    }

    public function test_handles_empty_schedule_data_and_creates_empty_schedule(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        $this->updateOfficeHours->execute($doctor, []);

        $doctor->refresh();
        $schedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->get();

        // Should create an empty schedule to ensure doctor always has a schedule
        $this->assertCount(1, $schedules);
        
        $schedule = $schedules->first();
        $this->assertCount(0, $schedule->periods, 'Empty schedule should have no periods');
    }

    public function test_handles_days_with_no_periods(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        $scheduleData = [
            'monday' => ['am' => '1'],
            'tuesday' => [], // No periods
            'wednesday' => ['pm' => '1'],
        ];

        $this->updateOfficeHours->execute($doctor, $scheduleData);

        $doctor->refresh();
        $schedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->with('periods')
            ->get();

        // Should only create schedules for days with periods
        $this->assertCount(2, $schedules);
        
        // Verify tuesday is not in any schedule
        foreach ($schedules as $schedule) {
            $config = $schedule->frequency_config ?? [];
            $days = $config['days'] ?? [];
            $this->assertNotContains('tuesday', $days);
        }
    }

    public function test_doctor_automatically_gets_empty_schedule_when_created(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $schedules = $doctor->schedules()
            ->where('name', 'Office Hours')
            ->get();

        // Doctor should automatically have an empty schedule
        $this->assertCount(1, $schedules);
        
        $schedule = $schedules->first();
        $this->assertEquals('Office Hours', $schedule->name);
        $this->assertCount(0, $schedule->periods, 'New doctor should have empty schedule with no periods');
    }

    public function test_user_gets_empty_schedule_when_updated_to_doctor(): void
    {
        $user = User::factory()->create(['is_doctor' => false]);
        
        // Initially should have no schedules
        $this->assertCount(0, $user->schedules()->where('name', 'Office Hours')->get());

        // Update to doctor
        $user->update(['is_doctor' => true]);

        $user->refresh();
        $schedules = $user->schedules()
            ->where('name', 'Office Hours')
            ->get();

        // Should automatically get an empty schedule
        $this->assertCount(1, $schedules);
        
        $schedule = $schedules->first();
        $this->assertEquals('Office Hours', $schedule->name);
        $this->assertCount(0, $schedule->periods, 'Newly converted doctor should have empty schedule');
    }

    public function test_doctor_does_not_get_duplicate_schedule_if_already_exists(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        // Doctor should have one empty schedule from creation
        $initialCount = $doctor->schedules()->where('name', 'Office Hours')->count();
        $this->assertEquals(1, $initialCount);

        // Update is_doctor (even though it's already true) - should not create duplicate
        $doctor->update(['is_doctor' => true]);

        $doctor->refresh();
        $finalCount = $doctor->schedules()->where('name', 'Office Hours')->count();
        
        // Should still have only one schedule
        $this->assertEquals(1, $finalCount);
    }
}

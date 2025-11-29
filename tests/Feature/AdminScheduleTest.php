<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_schedule_show_page_loads(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $response = $this->get('/admin/doctors/' . $doctor->id . '/schedules');

        $response->assertStatus(200);
        $response->assertSee('Schedule Management');
    }

    public function test_admin_schedule_show_returns_404_for_non_doctor(): void
    {
        $patient = User::factory()->create(['is_doctor' => false]);

        $response = $this->get('/admin/doctors/' . $patient->id . '/schedules');

        $response->assertStatus(404);
    }

    public function test_admin_can_create_office_hours(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $response = $this->post('/admin/doctors/' . $doctor->id . '/schedules/office-hours', [
            'days' => ['monday', 'tuesday', 'wednesday'],
            'morning_start' => '09:00',
            'morning_end' => '12:00',
            'afternoon_start' => '14:00',
            'afternoon_end' => '17:00',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_office_hours_validates_required_fields(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $response = $this->post('/admin/doctors/' . $doctor->id . '/schedules/office-hours', []);

        $response->assertSessionHasErrors(['days', 'morning_start', 'morning_end', 'afternoon_start', 'afternoon_end']);
    }

    public function test_admin_can_create_blocked_period(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $date = now()->addDay()->format('Y-m-d');

        $response = $this->post('/admin/doctors/' . $doctor->id . '/schedules/blocked', [
            'name' => 'Lunch Break',
            'date' => $date,
            'start_time' => '12:00',
            'end_time' => '13:00',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_schedule_preview_page_loads(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $date = now()->addDay()->format('Y-m-d');

        $response = $this->get('/admin/doctors/' . $doctor->id . '/schedules/preview?date=' . $date);

        $response->assertStatus(200);
        $response->assertSee('Preview Bookable Slots');
    }
}

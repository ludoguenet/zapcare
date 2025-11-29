<?php

namespace Tests\Feature;

use App\Actions\Doctors\CreateOfficeHours;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_create_page_loads(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $date = now()->addDay()->format('Y-m-d');

        $response = $this->get('/doctors/' . $doctor->id . '/appointments/create?date=' . $date);

        $response->assertStatus(200);
        $response->assertSee($doctor->name);
    }

    public function test_appointment_create_returns_404_for_non_doctor(): void
    {
        $patient = User::factory()->create(['is_doctor' => false]);

        $response = $this->get('/doctors/' . $patient->id . '/appointments/create');

        $response->assertStatus(404);
    }

    public function test_appointment_can_be_booked(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        
        // Create office hours for tomorrow
        $tomorrow = now()->addDay();
        $createOfficeHours = new CreateOfficeHours();
        $createOfficeHours->execute(
            $doctor,
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            '09:00',
            '12:00',
            '14:00',
            '17:00'
        );

        $date = $tomorrow->format('Y-m-d');
        $dayName = strtolower($tomorrow->format('l'));

        // Only proceed if tomorrow is a weekday
        if (in_array($dayName, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])) {
            $response = $this->post('/doctors/' . $doctor->id . '/appointments', [
                'date' => $date,
                'start_time' => '09:00',
                'end_time' => '10:00',
                'patient_name' => 'John Doe',
            ]);

            $response->assertRedirect(route('appointments.confirmation'));
            $response->assertSessionHas('success');
        } else {
            $this->markTestSkipped('Tomorrow is not a weekday, skipping appointment booking test');
        }
    }

    public function test_appointment_booking_validates_required_fields(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $response = $this->post('/doctors/' . $doctor->id . '/appointments', []);

        $response->assertSessionHasErrors(['date', 'start_time', 'end_time', 'patient_name']);
    }

    public function test_appointment_booking_validates_date_is_not_past(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $pastDate = now()->subDay()->format('Y-m-d');

        $response = $this->post('/doctors/' . $doctor->id . '/appointments', [
            'date' => $pastDate,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'patient_name' => 'John Doe',
        ]);

        $response->assertSessionHasErrors(['date']);
    }

    public function test_appointment_booking_validates_end_time_after_start_time(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $date = now()->addDay()->format('Y-m-d');

        $response = $this->post('/doctors/' . $doctor->id . '/appointments', [
            'date' => $date,
            'start_time' => '10:00',
            'end_time' => '09:00',
            'patient_name' => 'John Doe',
        ]);

        $response->assertSessionHasErrors(['end_time']);
    }

    public function test_appointment_confirmation_page_loads(): void
    {
        $response = $this->get('/appointments/confirmation');

        $response->assertStatus(200);
        $response->assertSee('confirmed');
    }
}

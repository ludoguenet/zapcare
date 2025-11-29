<?php

namespace Tests\Feature;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;
    public function test_doctors_index_page_loads(): void
    {
        $response = $this->get('/doctors');

        $response->assertStatus(200);
        $response->assertSee('Our Doctors');
    }

    public function test_doctors_index_shows_doctors(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $response = $this->get('/doctors');

        $response->assertStatus(200);
        $response->assertSee($doctor->name);
    }

    public function test_doctors_index_filters_by_specialty(): void
    {
        $specialty = Specialty::factory()->create(['name' => 'Cardiology']);
        $doctor1 = User::factory()->create(['is_doctor' => true]);
        $doctor2 = User::factory()->create(['is_doctor' => true]);
        
        $doctor1->specialties()->attach($specialty);

        $response = $this->get('/doctors?specialty_id=' . $specialty->id);

        $response->assertStatus(200);
        $response->assertSee($doctor1->name);
        $response->assertDontSee($doctor2->name);
    }

    public function test_doctor_show_page_loads(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $response = $this->get('/doctors/' . $doctor->id);

        $response->assertStatus(200);
        $response->assertSee($doctor->name);
    }

    public function test_doctor_show_returns_404_for_non_doctor(): void
    {
        $patient = User::factory()->create(['is_doctor' => false]);

        $response = $this->get('/doctors/' . $patient->id);

        $response->assertStatus(404);
    }

    public function test_doctor_slots_endpoint_returns_json(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $date = now()->addDay()->format('Y-m-d');

        $response = $this->get('/doctors/' . $doctor->id . '/slots?date=' . $date);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'slots',
            'date'
        ]);
    }

    public function test_doctor_slots_validates_date(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);

        $response = $this->get('/doctors/' . $doctor->id . '/slots');

        // GET requests with validation errors redirect back
        $response->assertStatus(302);
    }

    public function test_doctor_slots_rejects_past_dates(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $pastDate = now()->subDay()->format('Y-m-d');

        $response = $this->get('/doctors/' . $doctor->id . '/slots?date=' . $pastDate);

        // GET requests with validation errors redirect back
        $response->assertStatus(302);
    }
}

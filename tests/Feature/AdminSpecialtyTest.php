<?php

namespace Tests\Feature;

use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSpecialtyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_specialties_index_page_loads(): void
    {
        $response = $this->get('/admin/specialties');

        $response->assertStatus(200);
        $response->assertSee('Specialties');
    }

    public function test_admin_specialties_index_shows_specialties(): void
    {
        $specialty = Specialty::factory()->create();

        $response = $this->get('/admin/specialties');

        $response->assertStatus(200);
        $response->assertSee($specialty->name);
    }

    public function test_admin_specialty_create_page_loads(): void
    {
        $response = $this->get('/admin/specialties/create');

        $response->assertStatus(200);
        $response->assertSee('Create Specialty');
    }

    public function test_admin_can_create_specialty(): void
    {
        $response = $this->post('/admin/specialties', [
            'name' => 'Cardiology',
            'description' => 'Heart and cardiovascular system',
        ]);

        $response->assertRedirect(route('admin.specialties.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('specialties', [
            'name' => 'Cardiology',
            'description' => 'Heart and cardiovascular system',
        ]);
    }

    public function test_admin_specialty_create_validates_name_required(): void
    {
        $response = $this->post('/admin/specialties', [
            'description' => 'Some description',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_admin_specialty_create_validates_name_uniqueness(): void
    {
        Specialty::factory()->create(['name' => 'Cardiology']);

        $response = $this->post('/admin/specialties', [
            'name' => 'Cardiology',
            'description' => 'Duplicate',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_admin_specialty_edit_page_loads(): void
    {
        $specialty = Specialty::factory()->create();

        $response = $this->get('/admin/specialties/' . $specialty->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee($specialty->name);
    }

    public function test_admin_can_update_specialty(): void
    {
        $specialty = Specialty::factory()->create();

        $response = $this->put('/admin/specialties/' . $specialty->id, [
            'name' => 'Updated Cardiology',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect(route('admin.specialties.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('specialties', [
            'id' => $specialty->id,
            'name' => 'Updated Cardiology',
            'description' => 'Updated description',
        ]);
    }

    public function test_admin_can_delete_specialty(): void
    {
        $specialty = Specialty::factory()->create();

        $response = $this->delete('/admin/specialties/' . $specialty->id);

        $response->assertRedirect(route('admin.specialties.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('specialties', [
            'id' => $specialty->id,
        ]);
    }
}

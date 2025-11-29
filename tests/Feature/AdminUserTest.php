<?php

namespace Tests\Feature;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_index_page_loads(): void
    {
        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Users Management');
    }

    public function test_admin_users_index_shows_users(): void
    {
        $user = User::factory()->create();

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_admin_user_edit_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->get('/admin/users/' . $user->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create();

        $response = $this->put('/admin/users/' . $user->id, [
            'name' => 'Updated Name',
            'email' => $user->email,
            'is_doctor' => true,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'is_doctor' => true,
        ]);
    }

    public function test_admin_can_assign_specialties_to_doctor(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $specialty1 = Specialty::factory()->create();
        $specialty2 = Specialty::factory()->create();

        $response = $this->put('/admin/users/' . $doctor->id, [
            'name' => $doctor->name,
            'email' => $doctor->email,
            'is_doctor' => true,
            'specialties' => [$specialty1->id, $specialty2->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        
        $this->assertTrue($doctor->fresh()->specialties->contains($specialty1));
        $this->assertTrue($doctor->fresh()->specialties->contains($specialty2));
    }

    public function test_admin_can_remove_specialties_from_doctor(): void
    {
        $doctor = User::factory()->create(['is_doctor' => true]);
        $specialty = Specialty::factory()->create();
        $doctor->specialties()->attach($specialty);

        $response = $this->put('/admin/users/' . $doctor->id, [
            'name' => $doctor->name,
            'email' => $doctor->email,
            'is_doctor' => true,
            'specialties' => [],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        
        $this->assertFalse($doctor->fresh()->specialties->contains($specialty));
    }

    public function test_admin_user_update_validates_email_uniqueness(): void
    {
        $user1 = User::factory()->create(['email' => 'test@example.com']);
        $user2 = User::factory()->create();

        $response = $this->put('/admin/users/' . $user2->id, [
            'name' => $user2->name,
            'email' => 'test@example.com',
            'is_doctor' => false,
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}

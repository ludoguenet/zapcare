<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ZapCare');
        $response->assertSee('Book Care in a Flash');
    }

    public function test_homepage_displays_specialties_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Our Specialties');
    }

    public function test_homepage_handles_empty_specialties(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('No specialties available yet');
    }
}

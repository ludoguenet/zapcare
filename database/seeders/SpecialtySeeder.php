<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Cardiology',
                'description' => 'Heart and cardiovascular system disorders',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, and nail conditions',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Medical care for infants, children, and adolescents',
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Musculoskeletal system including bones, joints, and muscles',
            ],
            [
                'name' => 'Neurology',
                'description' => 'Nervous system disorders including brain and spinal cord',
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Mental health and behavioral disorders',
            ],
            [
                'name' => 'General Practice',
                'description' => 'Primary care and general medical services',
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Eye and vision care',
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Women\'s reproductive health',
            ],
            [
                'name' => 'Internal Medicine',
                'description' => 'Prevention, diagnosis, and treatment of adult diseases',
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::create($specialty);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com'],
            ['name' => 'Mary Johnson', 'email' => 'mary.johnson@example.com'],
            ['name' => 'Robert Williams', 'email' => 'robert.williams@example.com'],
            ['name' => 'Jennifer Brown', 'email' => 'jennifer.brown@example.com'],
            ['name' => 'Michael Davis', 'email' => 'michael.davis@example.com'],
            ['name' => 'Linda Miller', 'email' => 'linda.miller@example.com'],
            ['name' => 'William Garcia', 'email' => 'william.garcia@example.com'],
            ['name' => 'Elizabeth Martinez', 'email' => 'elizabeth.martinez@example.com'],
            ['name' => 'Richard Anderson', 'email' => 'richard.anderson@example.com'],
            ['name' => 'Susan Taylor', 'email' => 'susan.taylor@example.com'],
            ['name' => 'Joseph Thomas', 'email' => 'joseph.thomas@example.com'],
            ['name' => 'Jessica Jackson', 'email' => 'jessica.jackson@example.com'],
            ['name' => 'Thomas White', 'email' => 'thomas.white@example.com'],
            ['name' => 'Sarah Harris', 'email' => 'sarah.harris@example.com'],
            ['name' => 'Charles Martin', 'email' => 'charles.martin@example.com'],
            ['name' => 'Nancy Thompson', 'email' => 'nancy.thompson@example.com'],
            ['name' => 'Daniel Garcia', 'email' => 'daniel.garcia@example.com'],
            ['name' => 'Karen Martinez', 'email' => 'karen.martinez@example.com'],
            ['name' => 'Matthew Robinson', 'email' => 'matthew.robinson@example.com'],
            ['name' => 'Betty Clark', 'email' => 'betty.clark@example.com'],
            ['name' => 'Anthony Rodriguez', 'email' => 'anthony.rodriguez@example.com'],
            ['name' => 'Helen Lewis', 'email' => 'helen.lewis@example.com'],
            ['name' => 'Mark Lee', 'email' => 'mark.lee@example.com'],
            ['name' => 'Donna Walker', 'email' => 'donna.walker@example.com'],
            ['name' => 'Paul Hall', 'email' => 'paul.hall@example.com'],
            ['name' => 'Carol Allen', 'email' => 'carol.allen@example.com'],
            ['name' => 'Steven Young', 'email' => 'steven.young@example.com'],
            ['name' => 'Michelle King', 'email' => 'michelle.king@example.com'],
            ['name' => 'Andrew Wright', 'email' => 'andrew.wright@example.com'],
            ['name' => 'Laura Lopez', 'email' => 'laura.lopez@example.com'],
        ];

        foreach ($patients as $patient) {
            User::create([
                'name' => $patient['name'],
                'email' => $patient['email'],
                'password' => Hash::make('password'),
                'is_doctor' => false,
                'email_verified_at' => now(),
            ]);
        }
    }
}

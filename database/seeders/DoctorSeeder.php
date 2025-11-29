<?php

namespace Database\Seeders;

use App\Actions\Doctors\CreateOfficeHours;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createOfficeHours = new CreateOfficeHours();

        $doctors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@clinic.com',
                'specialties' => ['Cardiology', 'Internal Medicine'],
                'schedule' => [
                    'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    'morning_start' => '08:00',
                    'morning_end' => '12:00',
                    'afternoon_start' => '13:00',
                    'afternoon_end' => '17:00',
                ],
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@clinic.com',
                'specialties' => ['Pediatrics'],
                'schedule' => [
                    'days' => ['monday', 'wednesday', 'friday'],
                    'morning_start' => '09:00',
                    'morning_end' => '13:00',
                    'afternoon_start' => '14:00',
                    'afternoon_end' => '18:00',
                ],
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@clinic.com',
                'specialties' => ['Dermatology'],
                'schedule' => [
                    'days' => ['tuesday', 'thursday', 'saturday'],
                    'morning_start' => '10:00',
                    'morning_end' => '14:00',
                    'afternoon_start' => '15:00',
                    'afternoon_end' => '19:00',
                ],
            ],
            [
                'name' => 'Dr. James Wilson',
                'email' => 'james.wilson@clinic.com',
                'specialties' => ['Orthopedics'],
                'schedule' => [
                    'days' => ['monday', 'tuesday', 'wednesday', 'thursday'],
                    'morning_start' => '07:00',
                    'morning_end' => '11:00',
                    'afternoon_start' => '12:00',
                    'afternoon_end' => '16:00',
                ],
            ],
            [
                'name' => 'Dr. Lisa Anderson',
                'email' => 'lisa.anderson@clinic.com',
                'specialties' => ['Neurology', 'General Practice'],
                'schedule' => [
                    'days' => ['monday', 'wednesday', 'friday'],
                    'morning_start' => '08:30',
                    'morning_end' => '12:30',
                    'afternoon_start' => '13:30',
                    'afternoon_end' => '17:30',
                ],
            ],
            [
                'name' => 'Dr. Robert Martinez',
                'email' => 'robert.martinez@clinic.com',
                'specialties' => ['Psychiatry'],
                'schedule' => [
                    'days' => ['tuesday', 'thursday', 'friday'],
                    'morning_start' => '09:00',
                    'morning_end' => '13:00',
                    'afternoon_start' => '14:00',
                    'afternoon_end' => '18:00',
                ],
            ],
            [
                'name' => 'Dr. Patricia Brown',
                'email' => 'patricia.brown@clinic.com',
                'specialties' => ['Ophthalmology'],
                'schedule' => [
                    'days' => ['monday', 'tuesday', 'thursday', 'friday'],
                    'morning_start' => '08:00',
                    'morning_end' => '12:00',
                    'afternoon_start' => '13:00',
                    'afternoon_end' => '17:00',
                ],
            ],
            [
                'name' => 'Dr. David Thompson',
                'email' => 'david.thompson@clinic.com',
                'specialties' => ['Gynecology', 'General Practice'],
                'schedule' => [
                    'days' => ['monday', 'wednesday', 'thursday', 'saturday'],
                    'morning_start' => '09:00',
                    'morning_end' => '13:00',
                    'afternoon_start' => '14:00',
                    'afternoon_end' => '18:00',
                ],
            ],
        ];

        foreach ($doctors as $doctorData) {
            $doctor = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('password'),
                'is_doctor' => true,
                'email_verified_at' => now(),
            ]);

            // Attach specialties
            $specialtyIds = Specialty::whereIn('name', $doctorData['specialties'])->pluck('id');
            $doctor->specialties()->attach($specialtyIds);

            // Create office hours schedule
            $schedule = $doctorData['schedule'];
            $createOfficeHours->execute(
                $doctor,
                $schedule['days'],
                $schedule['morning_start'],
                $schedule['morning_end'],
                $schedule['afternoon_start'],
                $schedule['afternoon_end']
            );
        }
    }
}

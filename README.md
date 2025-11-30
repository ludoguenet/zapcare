# ZapCare

A modern, minimalist clinic scheduling application built with Laravel, Blade, Alpine.js, and Laravel Zap.

## Features

- **Single User Model**: Uses a single `User` model with an `is_doctor` boolean flag
- **Doctor Management**: Doctors can have multiple specialties
- **Appointment Booking**: Patients can browse doctors, view available slots, and book appointments
- **Schedule Management**: Admin can manage doctor schedules using Zap
- **Clean UI**: Built with Tailwind CSS and Alpine.js for a modern, responsive interface

## Requirements

- PHP 8.5+
- Composer
- Node.js & NPM
- Database (MySQL, PostgreSQL, or SQLite)

## Installation

1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install NPM dependencies:
   ```bash
   npm install
   ```

4. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database in `.env`

7. Run migrations:
   ```bash
   php artisan migrate
   ```

8. Build assets:
   ```bash
   npm run build
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

### Setting Up Doctors

1. Go to `/admin/users`
2. Edit a user and check "Is Doctor"
3. Assign specialties to the doctor
4. Go to `/admin/doctors/{doctor}/schedules` to set up office hours

### Creating Office Hours

1. Navigate to Admin → Schedules
2. Select days of the week
3. Set morning and afternoon time periods
4. Click "Create Office Hours"

### Booking Appointments

1. Browse doctors at `/doctors`
2. Click on a doctor to view their profile
3. Select a date
4. Choose an available time slot
5. Enter patient name and confirm booking

## Technology Stack

- **Laravel 12**: PHP framework
- **Blade**: Templating engine
- **Alpine.js**: Lightweight JavaScript framework
- **Laravel Zap**: Scheduling package
- **Tailwind CSS**: Utility-first CSS framework

## Project Structure

- `app/Actions/Doctors/`: Action classes for scheduling operations
- `app/Http/Controllers/`: Controllers for public and admin routes
- `resources/views/`: Blade templates
- `database/migrations/`: Database migrations

## License

MIT

<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Public doctor routes
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
Route::get('/doctors/{doctor}/slots', [DoctorController::class, 'slots'])->name('doctors.slots');

// Appointment booking routes
Route::get('/doctors/{doctor}/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/doctors/{doctor}/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/appointments/confirmation', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Specialties
    Route::resource('specialties', SpecialtyController::class);

    // Schedules
    Route::get('/doctors/{doctor}/schedules', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::post('/doctors/{doctor}/schedules/office-hours', [ScheduleController::class, 'createOfficeHours'])->name('schedules.office-hours');
    Route::post('/doctors/{doctor}/schedules/blocked', [ScheduleController::class, 'createBlockedPeriod'])->name('schedules.blocked');
    Route::get('/doctors/{doctor}/schedules/preview', [ScheduleController::class, 'previewSlots'])->name('schedules.preview');
});

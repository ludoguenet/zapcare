<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zap\Facades\Zap;
use Zap\Models\Concerns\HasSchedules;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasSchedules;

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Ensure doctors always have an empty schedule when created
        static::created(function (User $user) {
            if ($user->is_doctor) {
                $user->ensureEmptySchedule();
            }
        });

        // Ensure doctors have an empty schedule when is_doctor is set to true
        static::updated(function (User $user) {
            if ($user->is_doctor && $user->wasChanged('is_doctor')) {
                $user->ensureEmptySchedule();
            }
        });
    }

    /**
     * Ensure the doctor has an empty "Office Hours" schedule.
     */
    public function ensureEmptySchedule(): void
    {
        // Check if doctor already has an "Office Hours" schedule
        $hasOfficeHours = $this->schedules()
            ->where('name', 'Office Hours')
            ->exists();

        if (!$hasOfficeHours) {
            Zap::for($this)
                ->named('Office Hours')
                ->availability()
                ->from(now()->toDateString())
                ->weekly([])
                ->save();
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_doctor',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_doctor' => 'boolean',
        ];
    }

    /**
     * Get the specialties for the doctor.
     */
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class);
    }

    /**
     * Check if user is a doctor.
     */
    public function isDoctor(): bool
    {
        return $this->is_doctor;
    }

    /**
     * Check if user is a patient.
     */
    public function isPatient(): bool
    {
        return !$this->is_doctor;
    }
}

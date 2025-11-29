<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\SpecialtyFactory;

class Specialty extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return SpecialtyFactory::new();
    }

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the doctors for the specialty.
     */
    public function doctors()
    {
        return $this->belongsToMany(User::class)->where('is_doctor', true);
    }
}

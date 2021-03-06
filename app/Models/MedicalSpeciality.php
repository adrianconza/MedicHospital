<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class MedicalSpeciality extends Model
{
    use HasFactory, SoftDeletes, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'medical_specialities.name' => 10,
        ]
    ];

    /**
     * Get the doctors for the medical speciality.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the appointments for the medical speciality.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

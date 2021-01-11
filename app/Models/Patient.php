<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Patient extends Model
{
    use HasFactory, Notifiable, SoftDeletes, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'identification',
        'name',
        'last_name',
        'phone',
        'address',
        'birthday',
        'gender',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'patients.identification' => 10,
            'patients.name' => 10,
            'patients.last_name' => 10,
            'patients.email' => 5,
        ]
    ];

    /**
     * Get the users for the patient.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the city for the patient.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

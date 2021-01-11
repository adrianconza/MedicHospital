<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time',
        'end_time',
        'reason',
    ];

    /**
     * Get the patient for the appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the medical speciality for the appointment.
     */
    public function medicalSpeciality()
    {
        return $this->belongsTo(MedicalSpeciality::class);
    }

    /**
     * Get the doctor for the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

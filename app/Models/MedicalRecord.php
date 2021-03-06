<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The qualify of the appointment
     *
     * @var array
     */
    const QUALIFY = [
        'RG' => 'Regular',
        'BN' => 'Bueno',
        'EX' => 'Excelente',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'diagnosis',
        'qualify',
    ];

    /**
     * Get the appointment for the medical record.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the recipes for the medical record.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Get the medical exams for the medical record.
     */
    public function medicalExams()
    {
        return $this->hasMany(MedicalExam::class);
    }
}

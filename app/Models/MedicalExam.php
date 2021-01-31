<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalExam extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The result of the medical exam.
     *
     * @var array
     */
    const RESULTS = [
        'BN' => 'Bien',
        'RG' => 'Regular',
        'ML' => 'Mal',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'result',
    ];

    /**
     * Get the imaging exam for the medical exam.
     */
    public function imagingExam()
    {
        return $this->belongsTo(ImagingExam::class);
    }

    /**
     * Get the laboratory exam for the medical exam.
     */
    public function laboratoryExam()
    {
        return $this->belongsTo(LaboratoryExam::class);
    }

    /**
     * Get the medical record for the medical exam.
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}

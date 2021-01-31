<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class LaboratoryExam extends Model
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
            'laboratory_exams.name' => 10,
        ]
    ];

    /**
     * Get the medical exams for the laboratory exam.
     */
    public function medicalExams()
    {
        return $this->hasMany(MedicalExam::class);
    }
}

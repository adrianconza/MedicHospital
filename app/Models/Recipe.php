<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The units of the recipes.
     *
     * @var array
     */
    const UNITS = [
        'UND' => 'Unidad',
        'PST' => 'Pastilla',
        'BLS' => 'Blíster',
        'CAJ' => 'Caja',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'unit',
        'prescription',
    ];

    /**
     * Get the medicine for the recipe.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * Get the medical record for the recipe.
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}

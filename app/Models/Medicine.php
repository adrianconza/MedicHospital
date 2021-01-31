<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Medicine extends Model
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
            'medicines.name' => 10,
        ]
    ];

    /**
     * Get the recipes for the medicine.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}

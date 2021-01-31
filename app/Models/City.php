<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the cities and provinces.
     */
    public static function citiesAndProvinces()
    {
        return City::query()->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->orderBy('provinces.name')->orderBy('cities.name')->select('cities.*')->get();
    }

    /**
     * Get the province for the city.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}

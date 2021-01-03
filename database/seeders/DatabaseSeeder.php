<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\ImagingExam;
use App\Models\LaboratoryExam;
use App\Models\MedicalSpeciality;
use App\Models\Province;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        MedicalSpeciality::factory(50)->create();
        LaboratoryExam::factory(50)->create();
        ImagingExam::factory(50)->create();
        Province::factory(10)->create()->each(function ($province) {
            $province->cities()->saveMany(City::factory(5)->make());
        });
    }
}

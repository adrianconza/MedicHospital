<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\ImagingExam;
use App\Models\LaboratoryExam;
use App\Models\MedicalSpeciality;
use App\Models\Province;
use App\Models\Role;
use App\Models\User;
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
        Province::factory(10)->create()->each(function ($province) {
            $province->cities()->saveMany(City::factory(5)->make());
        });

        $cities = City::all();
        User::factory(50)->make()->each(function ($user) use ($cities) {
            $user->city()->associate($cities->random(1)->first());
            $user->save();
        });
        $this->call(RoleSeeder::class);
        $roles = Role::all();
        User::all()->each(function ($user) use ($roles) {
            $user->roles()->attach(
                $roles->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        MedicalSpeciality::factory(50)->create();
        LaboratoryExam::factory(50)->create();
        ImagingExam::factory(50)->create();
    }
}

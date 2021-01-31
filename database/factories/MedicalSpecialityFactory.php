<?php

namespace Database\Factories;

use App\Models\MedicalSpeciality;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalSpecialityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalSpeciality::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
        ];
    }
}

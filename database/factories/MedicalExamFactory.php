<?php

namespace Database\Factories;

use App\Models\MedicalExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalExamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalExam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'result' => $this->faker->randomElement(array_keys(MedicalExam::RESULTS))
        ];
    }
}

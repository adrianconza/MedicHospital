<?php

namespace Database\Factories;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = new Carbon($this->faker->dateTimeBetween('now', '+1 month'));
        $endTime = $startTime->copy()->addMinutes(Appointment::TIME);
        return [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => '00:' . Appointment::TIME,
            'reason' => $this->faker->text,
            'type' => $this->faker->randomElement(array_keys(Appointment::TYPE))
        ];
    }
}

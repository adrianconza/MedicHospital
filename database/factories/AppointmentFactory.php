<?php

namespace Database\Factories;

use App\Models\Appointment;
use DateInterval;
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
        $startTime = $this->faker->dateTimeBetween('now', '+1 month');
        $endTime = (clone $startTime)->add(new DateInterval('PT30M'));
        return [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => '00:30',
            'reason' => $this->faker->text,
        ];
    }
}

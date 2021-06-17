<?php

namespace Database\Factories;

use App\Models\WeightState;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightStateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = WeightState::class;

    /**
     * Define the model's default state.
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'date' => '2021-06-01',
            'weight_amount' => $this->faker->numberBetween(60, 80),
            'body_temperature' => $this->faker->numberBetween(36, 40),
            'fat_ratio' => $this->faker->numberBetween(20, 30),
        ];
    }
}

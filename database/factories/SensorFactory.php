<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = $this->faker->numberBetween(0, 50);
        $max = $this->faker->numberBetween($min + 1, 200);

        $name = $this->faker->words(2, true);

        return [

            'name' => $name,
            'key'  => Device::generateKey($name),

            'description' => $this->faker->optional()->sentence(),
            'display_sort_order' => $this->faker->optional()->numberBetween(0, 10),

            'required' => $this->faker->boolean(),

            'min_value' => $min,
            'max_value' => $max,

            'unit_type' => $this->faker->optional()->randomElement(['°C', '%', 'bar', 'ppm']),
            'data_type' => $this->faker->numberBetween(0, 3),

            'deleted_at' => null,
        ];
    }
}

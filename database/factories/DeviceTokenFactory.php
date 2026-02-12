<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceToken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<DeviceToken>
 */
class DeviceTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plainToken = DeviceToken::makePlainToken();

        return [
            'device_id'    => Device::factory(),
            'prefix'       => DeviceToken::createPrefix($plainToken),
            'token_hash'   => DeviceToken::hashToken($plainToken),
            'rate_limit'   => fake()->numberBetween(10, 120),
            'last_used_at' => fake()->optional(0.7)->dateTimeBetween('-30 days'),
        ];
    }
}

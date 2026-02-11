<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Sensor;
use App\Models\User;
use Database\Factories\SensorFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);

        for ($i = 0; $i < 10; $i++) {
            $device = Device::factory()->create();

            Sensor::factory()
                ->count(rand(0, 5))
                ->for($device)
                ->create();
        }
    }
}

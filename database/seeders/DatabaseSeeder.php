<?php

namespace Database\Seeders;

use App\Enums\DataType;
use App\Models\Device;
use App\Models\DeviceToken;
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

        /*for ($i = 0; $i < 10; $i++) {
            $device = Device::factory()->create();

            DeviceToken::factory()->for($device)->create();

            Sensor::factory()
                ->count(rand(0, 5))
                ->for($device)
                ->create();
        }*/

        $user = User::factory()->create([
            'name'  => 'megosztott',
            'email' => 'megosztott@gmail.com',
            'password' => bcrypt('megosztott'),
        ]);

        $device = Device::factory()->create([
            'name' => 'Test device',
            'owner_user_id' => $user->id,
        ]);

        Sensor::factory()->for($device)->create([
            'name'        => 'Test sensor',
            'key'         => Device::generateKey('Test sensor'),
            'description' => 'Test sensor description',
            'required'    => true,
            'unit_type'   => 'Celsius',
            'data_type'   => DataType::FLOAT,
            'min_value'   => 0,
            'max_value'   => 100,
        ]);

        Sensor::factory()->for($device)->create([
            'name'        => 'Test sensor 2',
            'key'         => Device::generateKey('Test sensor 2'),
            'description' => 'Test sensor 2 description',
            'required'    => false,
            'unit_type'   => 'Celsius',
            'data_type'   => DataType::FLOAT,
            'min_value'   => 0,
            'max_value'   => 100,
        ]);

        $token = DeviceToken::factory()->for($device)->create([
            'token_hash' => DeviceToken::hashToken("nemtudom"),
        ]);
    }
}

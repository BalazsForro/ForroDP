<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@localhost.com',
            'password' => bcrypt('admin123admin'),
        ])->assignRole('admin');
    }
}

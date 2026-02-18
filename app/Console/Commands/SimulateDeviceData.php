<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SimulateDeviceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:device-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send fake sensor data to local API';

    /**
     * Execute the console command.
     * @throws ConnectionException
     */
    public function handle(): void
    {
        // php artisan schedule:work
        $token = config('services.device.token');

        $response = Http::withToken($token)
            ->acceptJson()
            ->post(config('services.device.endpoint'), [
                'test_sensor_2' => rand(0, 10),
                'test_sensor'   => rand(0, 100),
            ]);

        if ($response->failed()) {
            $this->error('Request failed: '.$response->body());
            return;
        }

        $this->info('Data sent successfully.');
    }
}

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
        $token = config('services.device.token'); // vagy .env-ből

        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'X-Device-Token' => $token,
                ])
                ->post(config('services.device.endpoint'), [
                    'test_sensor_2' => rand(0, 100),
                    'test_sensor'   => rand(0, 100),
                ]);

            $asd = '';
        }catch (ConnectionException $e){
            dd($e->getMessage());
        }

        /*$response = Http::withToken($token)
            ->acceptJson()
            ->post(config('services.device.endpoint'), [
                'test_sensor_2' => rand(0, 100),
                'test_sensor'   => rand(0, 100),
            ]);

        if ($response->failed()) {
            $this->error('Request failed: '.$response->body());
            return;
        }*/

        $this->info('Data sent successfully.');
    }
}

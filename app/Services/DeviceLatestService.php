<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Measurement;

class DeviceLatestService
{
    public function createOrUpdate(Device $device, Measurement $measurement): void
    {
        $device->latestState()->updateOrCreate([],[
            'measurement_id' => $measurement->id,
        ]);
    }
}

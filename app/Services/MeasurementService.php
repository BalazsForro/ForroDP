<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Measurement;

class MeasurementService
{
    public function storeFromDevicePayload(Device $device, array $payload): Measurement
    {
        return Measurement::create([
            'device_id' => $device->id,
            'raw_payload' => json_encode($payload),
            'is_valid' => json_last_error() === JSON_ERROR_NONE,
        ]);
    }
}

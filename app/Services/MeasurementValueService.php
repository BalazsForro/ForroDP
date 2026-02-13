<?php

namespace App\Services;

use App\Models\Measurement;
use App\Models\MeasurementValue;
use Illuminate\Support\Collection;

class MeasurementValueService
{
    public function createFromPayload(Measurement $measurement, Collection $sensors, array $payload): bool
    {
        foreach ($payload as $key => $value) {
            $sensor = $sensors->where('key', $key)->first();

            MeasurementValue::create([
                'measurement_id' => $measurement->id,
                'sensor_id'      => $sensor->id,
                'value'          => $value,
            ]);
        }

        return true;
    }
}

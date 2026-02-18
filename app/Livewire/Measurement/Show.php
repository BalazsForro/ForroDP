<?php

namespace App\Livewire\Measurement;

use App\Models\Device;
use App\Models\DeviceLatestState;
use App\Models\MeasurementValue;
use App\Models\Sensor;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    public ?int $deviceId = null;

    public int $limitLatestRows = 30; // optional: device-hez tartozó legutóbbi sorok
    public string $range = '24h';     // optional stats-hoz

    protected $listeners = [
        'open-measurement' => 'open',
    ];

    public function open(int $deviceId): void
    {
        $this->deviceId = $deviceId;
        $this->dispatch('bs-modal-open', id: 'measurementModal');
    }

    public function getDeviceProperty(): ?Device
    {
        return $this->deviceId ? Device::find($this->deviceId) : null;
    }

    public function getSensorsProperty(): Collection
    {
        if (!$this->deviceId) {
            return collect();
        }

        return Sensor::query()
            ->where('device_id', $this->deviceId)
            ->orderBy('display_sort_order')
            ->get();
    }

    public function getCurrentStateProperty(): \Illuminate\Support\Collection
    {
        if (!$this->deviceId) {
            return collect();
        }

        $latestMeasurementId = DeviceLatestState::query()
            ->where('device_id', $this->deviceId)
            ->value('measurement_id');

        if (!$latestMeasurementId) {
            return $this->sensors->map(fn (Sensor $sensor) => (object)[
                'sensor' => $sensor,
                'value' => null,
                'measured_at' => null,
                'is_valid' => null,
                'measurement_id' => null,
                'measurement_value_id' => null,
            ]);
        }

        $rows = MeasurementValue::query()
            ->with('measurement:id,is_valid,created_at')
            ->where('measurement_id', $latestMeasurementId)
            ->get()
            ->keyBy('sensor_id');

        return $this->sensors->map(function (Sensor $sensor) use ($rows, $latestMeasurementId) {
            $row = $rows->get($sensor->id);

            return (object)[
                'sensor'               => $sensor,
                'value'                => $row?->value,
                'measured_at'          => $row?->measurement?->created_at,
                'is_valid'             => $row?->measurement?->is_valid,
                'measurement_id'       => $latestMeasurementId,
                'measurement_value_id' => $row?->id,
            ];
        });
    }

    public function getLatestFeedProperty(): Collection
    {
        if (!$this->deviceId) {
            return collect();
        }

        return MeasurementValue::query()
            ->with(['measurement', 'sensor'])
            ->whereHas('measurement', fn($q) => $q->where('device_id', $this->deviceId))
            ->latest('id')
            ->limit($this->limitLatestRows)
            ->get();
    }

    public function render()
    {
        return view('livewire.measurement.show');
    }
}

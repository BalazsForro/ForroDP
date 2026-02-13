<?php

namespace App\Livewire\Measurement;

use App\Models\Device;
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

    /**
     * Jelenlegi állapot szenzoronként:
     * - legfrissebb MeasurementValue (value)
     * - measurement.is_valid + measurement.created_at
     *
     * Megoldás: subquery-vel megkeressük szenzoronként a max(id)-t a device mérésein belül.
     */
    public function getCurrentStateProperty(): Collection
    {
        if (!$this->deviceId) {
            return collect();
        }

        $latestIdsPerSensor = MeasurementValue::query()
            ->selectRaw('measurement_values.sensor_id, MAX(measurement_values.id) as max_id')
            ->join('measurements', 'measurements.id', '=', 'measurement_values.measurement_id')
            ->where('measurements.device_id', $this->deviceId)
            ->groupBy('measurement_values.sensor_id');

        $latestRows = MeasurementValue::query()
            ->with(['measurement'])
            ->joinSub($latestIdsPerSensor, 't', function ($join) {
                $join->on('measurement_values.id', '=', 't.max_id');
            })
            ->get()
            ->keyBy('sensor_id');

        // alakítsuk egy “view model” kollekcióvá a szenzorok sorrendjében
        return $this->sensors->map(function (Sensor $sensor) use ($latestRows) {
            $row = $latestRows->get($sensor->id);

            return (object)[
                'sensor'               => $sensor,
                'value'                => $row?->value,
                'measured_at'          => $row?->measurement?->created_at,
                'is_valid'             => $row?->measurement?->is_valid,
                'measurement_id'       => $row?->measurement_id,
                'measurement_value_id' => $row?->id,
            ];
        });
    }

    /**
     * Opcionális: device utolsó N value sora (összes szenzor vegyesen) a “live feed”-hez.
     */
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

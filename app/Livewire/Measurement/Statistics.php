<?php

namespace App\Livewire\Measurement;

use App\Models\Device;
use App\Models\MeasurementValue;
use App\Models\Sensor;
use Illuminate\Support\Collection;
use Livewire\Component;

class Statistics extends Component
{
    public ?int $deviceId = null;

    public string $range = '24h';

    public array $chartData = [];

    protected $listeners = [
        'open-statistics' => 'open',
    ];

    public function open(int $deviceId): void
    {
        $this->reset();
        $this->resetValidation();

        $this->deviceId = $deviceId;
        $this->range = '24h';
        $this->dispatch('bs-modal-open', id: 'statisticsModal');
        $this->dispatch('statistics-chart-data', data: $this->buildChartData());
    }

    public function updatedRange(): void
    {
        $this->dispatch('statistics-chart-data', data: $this->buildChartData());
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

    private function buildChartData(): array
    {
        if (!$this->deviceId) {
            return [];
        }

        $since = match ($this->range) {
            '1h'    => now()->subHour(),
            '6h'    => now()->subHours(6),
            '7d'    => now()->subDays(7),
            default => now()->subDay(),
        };

        $values = MeasurementValue::query()
            ->with('measurement:id,created_at,is_valid')
            ->whereHas('measurement', fn ($q) => $q
                ->where('device_id', $this->deviceId)
                ->where('is_valid', true)
                ->where('created_at', '>=', $since)
            )
            ->orderBy('measurement_id')
            ->get();

        $dateFormat = $this->range === '7d' ? 'M j H:i' : 'H:i:s';

        $result = [];
        foreach ($this->sensors as $sensor) {
            $sensorValues = $values->where('sensor_id', $sensor->id);

            $result[$sensor->id] = [
                'sensor_id'   => $sensor->id,
                'sensor_name' => $sensor->name,
                'unit'        => $sensor->unit_type,
                'labels'      => $sensorValues
                    ->map(fn ($v) => $v->measurement?->created_at?->format($dateFormat))
                    ->values()
                    ->toArray(),
                'values'      => $sensorValues
                    ->map(fn ($v) => is_numeric($v->value) ? (float) $v->value : $v->value)
                    ->values()
                    ->toArray(),
            ];
        }

        return $result;
    }

    public function render()
    {
        return view('livewire.measurement.statistics');
    }
}

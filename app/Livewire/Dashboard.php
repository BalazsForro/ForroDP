<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\Measurement;
use App\Models\MeasurementValue;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public function getDevicesProperty(): Collection
    {
        if (!auth()->check()) {
            return collect();
        }

        return Device::query()
            ->where(function ($q) {
                $q->where('owner_user_id', auth()->id())
                  ->orWhereHas('sharedUsers', fn ($s) => $s->where('users.id', auth()->id()));
            })
            ->with(['sensors', 'latestState.measurement:id,created_at'])
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();
    }

    public function getStatsProperty(): array
    {
        $devices   = $this->devices;
        $deviceIds = $devices->pluck('id');

        return [
            'total_devices'      => $devices->count(),
            'active_devices'     => $devices->where('is_active', 1)->count(),
            'total_sensors'      => $devices->sum(fn ($d) => $d->sensors->count()),
            'today_measurements' => $deviceIds->isNotEmpty()
                ? Measurement::whereIn('device_id', $deviceIds)->whereDate('created_at', today())->count()
                : 0,
        ];
    }

    public function getCurrentStatesProperty(): Collection
    {
        $latestIds = $this->devices
            ->pluck('latestState.measurement_id')
            ->filter()
            ->values();

        if ($latestIds->isEmpty()) {
            return collect();
        }

        return MeasurementValue::query()
            ->with('sensor:id,name,unit_type')
            ->whereIn('measurement_id', $latestIds)
            ->get()
            ->groupBy('measurement_id');
    }

    public function getRecentActivityProperty(): Collection
    {
        $deviceIds = $this->devices->pluck('id');

        if ($deviceIds->isEmpty()) {
            return collect();
        }

        return Measurement::query()
            ->with('device:id,name')
            ->withCount('values')
            ->whereIn('device_id', $deviceIds)
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
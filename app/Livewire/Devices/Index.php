<?php

namespace App\Livewire\Devices;

use App\Enums\Role;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Component
{
    public User $currentUser;

    public Collection $devices;

    // FILTER STATE
    public ?string $search = '';

    protected $listeners = [
        'device-created' => 'loadDevices'
    ];

    public function mount(): void
    {
        $this->currentUser = auth()->user();
        $this->devices = collect();

        $this->loadDevices();
    }

    public function render(): Factory|View
    {
        return view('livewire.devices.index');
    }

    public function updatedSearch(): void
    {
        $this->loadDevices();
    }

    public function loadDevices(): void
    {
        $query = Device::query()->with([
            'deviceType',
            'sensors' => fn($q) => $q->withTrashed(),
            'shares.sharedUser',
            'shares.sharedBy',
        ]);

        if (!$this->currentUser->hasRole(ROLE::ADMIN->value)) {
            $query->where(function ($q) {
                $q->where('owner_user_id', $this->currentUser->id)
                    ->orWhereHas('sharedUsers', function ($q) {
                        $q->where('users.id', $this->currentUser->id);
                    });
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas('sensors', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('sensors', fn($q) => $q->where('description', 'like', "%{$this->search}%"))
                    ->orWhereHas('sensors', fn($q) => $q->where('key', 'like', "%{$this->search}%"));
            });
        }

        $this->devices = $query->withTrashed()->orderByRaw('deleted_at IS NOT NULL')->latest()->get();
    }

    public function deleteDevice(int $deviceId): void
    {
        Device::find($deviceId)->delete();

        $this->loadDevices();
    }

    public function forceDeleteDevice(int $deviceId): void
    {
        Device::onlyTrashed()->find($deviceId)->forceDelete();
        $this->loadDevices();
    }

    public function revokeDevice(int $deviceId): void
    {
        Device::onlyTrashed()->find($deviceId)->restore();
        $this->loadDevices();
    }
}

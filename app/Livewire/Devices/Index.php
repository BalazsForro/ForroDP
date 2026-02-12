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
        $query = Device::query()->with('sensors');

        if (!$this->currentUser->hasRole(ROLE::ADMIN->value)) {
            $query->where('owner_user_id', $this->currentUser->id);
        }

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%")
                ->orWhereHas('sensors', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orWhereHas('sensors', fn($q) => $q->where('description', 'like', "%{$this->search}%"))
                ->orWhereHas('sensors', fn($q) => $q->where('key', 'like', "%{$this->search}%"));
        }

        $this->devices = $query->latest()->get();
    }

    public function edit(int $deviceId): void
    {
        $device = Device::find($deviceId);
        if ($device) {
            $this->dispatch('device-edit', deviceId: $deviceId);
        }
    }

}

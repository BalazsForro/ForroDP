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

    public function loadDevices(): void
    {
        $query = Device::query();

        if (!$this->currentUser->hasRole(ROLE::ADMIN->value)) {
            $query->where('owner_user_id', $this->currentUser->id);
        }

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $this->devices = $query->get();
    }

    public function updatedSearch(): void
    {
        $this->loadDevices();
    }

}

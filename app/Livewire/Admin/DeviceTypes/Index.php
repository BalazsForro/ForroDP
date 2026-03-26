<?php

namespace App\Livewire\Admin\DeviceTypes;

use App\Models\DeviceType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    public Collection $deviceTypes;

    protected $listeners = [
        'device-type-saved' => 'loadDeviceTypes',
    ];

    public function mount(): void
    {
        $this->loadDeviceTypes();
    }

    public function render(): Factory|View
    {
        return view('livewire.admin.device-types.index');
    }

    public function loadDeviceTypes(): void
    {
        $this->deviceTypes = DeviceType::orderBy('id')->get();
    }

    public function delete(int $id): void
    {
        DeviceType::findOrFail($id)->delete();
        $this->loadDeviceTypes();
        $this->dispatch('bs-toast-show', message: 'Device type deleted.');
    }
}
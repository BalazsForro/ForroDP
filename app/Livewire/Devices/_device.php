<?php

namespace App\Livewire\Devices;


use App\Models\Device;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use voku\helper\ASCII;

class _device extends _sensor
{
    public ?Device $device = null;

    #[Validate('string|required|max:45')]
    public string $deviceName = '';
    #[Validate('nullable|string|max:255')]
    public ?string $deviceDescription = null;

    #[Validate('integer|required|exists:device_types,id')]
    public int $deviceType = 1;

    protected function resetDevice(?int $deviceId = null): void
    {
        if ($deviceId) {
            $this->device = $this->getDevice($deviceId);
        }
        else {
            $this->device = null;
        }

        if ($this->device) {
            $this->deviceName = $this->device->name;
            $this->deviceDescription = $this->device->description;
            $this->deviceType = $this->device->device_type_id;

            $this->fetchSensors($this->device);
        }
        else {
            $this->deviceName = '';
            $this->deviceDescription = null;
            $this->deviceType = 1;

            $this->clearSensors();
        }
    }

    protected function getDevice(int $deviceId): Device
    {
        return Device::findOrFail($deviceId);
    }
}


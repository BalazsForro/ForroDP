<?php

namespace App\Livewire\Devices;


use App\Enums\DeviceType;
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

    #[Validate('integer|required|in:1,2,3,4')]
    public int $deviceType = DeviceType::ARDUINO->value;

    protected function resetDevice(?int $deviceId = null): void
    {
        if ($deviceId) {
            $this->getDevice($deviceId);
        }

        if ($this->device) {
            $this->deviceName = $this->device->name;
            $this->deviceDescription = $this->device->description;
            $this->deviceType = $this->device->type;

            $this->fetchSensors($this->device);
        }
        else {
            $this->deviceName = '';
            $this->deviceDescription = null;
            $this->deviceType = DeviceType::ARDUINO->value;

            $this->clearSensors();
        }
    }

    protected function getDevice(int $deviceId): void
    {
        $this->device = Device::findOrFail($deviceId);
    }
}


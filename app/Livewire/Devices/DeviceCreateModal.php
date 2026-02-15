<?php

namespace App\Livewire\Devices;

use App\Enums\DataType;
use App\Enums\DeviceType;
use App\Models\Device;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DeviceCreateModal extends _device
{
    protected $listeners = [
        'open-device-create' => 'open',
        'sensors-changed'    => 'onSensorsChanged',
        'sensors-invalid'    => 'onSensorsInvalid',
    ];

    public function render(): Factory|View
    {
        return view('livewire.devices.device-create-modal');
    }

    public function updated($propertyName, $value): void
    {
        if (str_starts_with($propertyName, 'sensors.') && str_ends_with($propertyName, '.name')) {
            $index = (int)explode('.', $propertyName)[1];

            $this->sensors[$index]['key'] = Device::generateKey($value);
        }
    }

    #[On('device-edit')]
    public function open(?int $deviceId = null): void
    {
        // reset state
        $this->resetValidation();

        $this->resetDevice($deviceId);

        //calling js function to open modal
        $this->dispatch('bs-modal-open', id: 'deviceCreateModal');
    }

    /**
     * @throws \Throwable
     */
    public function save(): void
    {
        $this->validate(
            rules     : $this->rules(),
            messages  : [],
            attributes: $this->validationAttributes(),
        );

        $userId = Auth::id();

        $token = DeviceToken::makePlainToken();

        $device = DB::transaction(function () use ($userId, &$token) {

            $device = Device::create([
                'owner_user_id' => $userId,
                'name'          => $this->deviceName,
                'description'   => $this->deviceDescription ?: null,
                'type'          => $this->deviceType,
            ]);

            $device->token()->create([
                'prefix'     => DeviceToken::createPrefix($token),
                'token_hash' => DeviceToken::hashToken($token),
                'rate_limit' => 60,
            ]);

            foreach ($this->sensors as $sensorData) {
                $device->sensors()->create($sensorData);
            }

            return $device;
        });

        // calling js function to close modal
        $this->dispatch('bs-modal-close', id: 'deviceCreateModal');

        $this->dispatch('bs-show-token', token: $token);

        // Index: update table
        $this->dispatch('device-created', deviceId: $device?->id);

        $this->dispatch('bs-toast-show', message: "Device was created successfully");
    }

    public function update(?int $deviceId = null)
    {
        $userId = Auth::id();

        $sensors = $this->sensors;

        $device = DB::transaction(function () use ($userId, $deviceId, $sensors) {

            $device = Device::find($deviceId);
            $device->update([
                'name'        => $this->deviceName,
                'description' => $this->deviceDescription ?: null,
                'type'        => $this->deviceType,
            ]);

            foreach ($sensors as $sensorData) {
                $device->sensors()->where('key', $sensorData['key'])->update($sensorData);
            }

            return $device;
        });

        // Index: update table
        $this->dispatch('device-created', deviceId: $device?->id);

        $this->dispatch('bs-toast-show', message: "Device was updated successfully");
    }

    protected function rules(): array
    {
        return [
            'deviceName'        => 'string|required|max:45',
            'deviceDescription' => 'nullable|string|max:255',
            'deviceType'        => 'integer|required|in:1,2,3,4',

            ...self::SENSORS_VALIDATE
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'sensors.*.name'               => 'sensor name',
            'sensors.*.description'        => 'sensor description',
            'sensors.*.display_sort_order' => 'sensor sort order',
            'sensors.*.required'           => 'sensor required',
            'sensors.*.min_value'          => 'sensor min value',
            'sensors.*.max_value'          => 'sensor max value',
            'sensors.*.unit_type'          => 'sensor unit type',
            'sensors.*.data_type'          => 'sensor data type',
        ];
    }
}

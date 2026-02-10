<?php

namespace App\Livewire\Devices;

use App\Enums\DataType;
use App\Enums\DeviceType;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DeviceCreateModal extends Component
{
    #[Validate('string|required|max:45')]
    public string $deviceName = '';

    #[Validate('nullable|string|max:255')]
    public ?string $deviceDescription = null;

    #[Validate('integer|required|in:1,2,3,4')]
    public int $deviceType = DeviceType::ARDUINO->value;

    #[Validate([
        'sensors'                      => 'array',
        'sensors.*.name'               => 'required|string|max:45',
        'sensors.*.description'        => 'nullable|string|max:255',
        'sensors.*.display_sort_order' => 'required|integer|min:0',
        'sensors.*.is_required'        => 'boolean',
        'sensors.*.min_value'          => 'nullable|numeric',
        'sensors.*.max_value'          => 'nullable|numeric',
        'sensors.*.unit_type'          => 'nullable|string|max:2',
        'sensors.*.data_type'          => 'required|in:1,2',
    ])]
    public array $sensors = [];
    public bool $sensorValidationFailed = false;

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

            $this->sensors[$index]['key'] = $this->generateKey($value);
        }
    }

    private function generateKey($value): string
    {
        return str($value)
            ->lower()
            ->ascii()
            ->replace([' ', '-', '_'], '_')
            ->trim('_')
            ->replaceMatches('/[^a-z0-9_]/', '')
            ->toString();
    }

    public function open(): void
    {
        // reset state
        $this->resetValidation();

        $this->deviceName = '';
        $this->deviceDescription = null;
        $this->deviceType = DeviceType::ARDUINO->value;
        $this->sensorValidationFailed = false;

        $this->sensors = [];

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

        $device = DB::transaction(function () use ($userId) {
            $device = Device::create([
                'owner_user_id' => $userId,
                'name'          => $this->deviceName,
                'description'   => $this->deviceDescription ?: null,
                'type'          => $this->deviceType,
            ]);

            foreach ($this->sensors as $sensorData) {
                $device->sensors()->create([
                    ...$sensorData,
                    'device_id' => $device->id,
                ]);
            }

            return $device;
        });

        // calling js function to close modal
        $this->dispatch('bs-modal-close', id: 'deviceCreateModal');

        // Index: update table
        $this->dispatch('device-created', deviceId: $device->id);

        $this->dispatch('bs-toast-show', message: 'Device created successfully');
    }

    protected function rules(): array
    {
        return [
            'deviceName'        => 'string|required|max:45',
            'deviceDescription' => 'nullable|string|max:255',
            'deviceType'        => 'integer|required|in:1,2,3,4',

            'sensors'                      => 'array',
            'sensors.*.name'               => 'required|string|max:45',
            'sensors.*.description'        => 'nullable|string|max:255',
            'sensors.*.display_sort_order' => 'required|integer|min:0',
            'sensors.*.is_required'        => 'boolean',
            'sensors.*.min_value'          => 'nullable|numeric',
            'sensors.*.max_value'          => 'nullable|numeric',
            'sensors.*.unit_type'          => 'nullable|string|max:2',
            'sensors.*.data_type'          => 'required|in:1,2',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'sensors.*.name'               => 'sensor name',
            'sensors.*.description'        => 'sensor description',
            'sensors.*.display_sort_order' => 'sensor sort order',
        ];
    }

    public function addSensor(): void
    {
        $this->sensors[] = [
            'name'               => '',
            'key'                => '',
            'description'        => '',
            'display_sort_order' => 0,
            'is_required'        => true,
            'min_value'          => null,
            'max_value'          => null,
            'unit_type'          => '',
            'data_type'          => DataType::FLOAT->value,
        ];

        // calling js to enable tooltips on newly added sensors
        $this->dispatch('bs-enable-tooltips');
    }

    public function removeSensor(int $index): void
    {
        unset($this->sensors[$index]);
    }
}

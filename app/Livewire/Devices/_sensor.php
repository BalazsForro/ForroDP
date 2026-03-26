<?php

namespace App\Livewire\Devices;

use App\Enums\DataType;
use App\Models\Device;
use App\Models\Sensor;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class _sensor extends Component
{
    public const SENSORS_VALIDATE = [
        'sensors'                      => 'array',
        'sensors.*.name'               => 'required|string|max:45',
        'sensors.*.description'        => 'nullable|string|max:255',
        'sensors.*.display_sort_order' => 'required|integer|min:0',
        'sensors.*.required'           => 'boolean',
        'sensors.*.min_value'          => 'nullable|numeric',
        'sensors.*.max_value'          => 'nullable|numeric',
        'sensors.*.unit_type'          => 'nullable|string|max:15',
        'sensors.*.data_type'          => 'required|int|in:1,2,3',
    ];

    #[Validate(self::SENSORS_VALIDATE)]
    public array $sensors = [];

    public function addSensor(): void
    {
        $this->sensors[] = [
            'id' => null,
            'name'               => '',
            'key'                => '',
            'description'        => '',
            'display_sort_order' => 0,
            'required'           => true,
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
        $sensor = $this->sensors[$index];

        if ($sensor['id']) {
            Sensor::find($sensor['id'])->delete();
        }

        unset($this->sensors[$index]);
    }

    protected function fetchSensors(Device $device): void
    {
        $this->clearSensors();

        foreach ($device->sensors()->orderBy('display_sort_order')->get() as $sensor) {
            $this->sensors[] = [
                'id'                 => $sensor->id,
                'name'               => $sensor->name,
                'key'                => $sensor->key,
                'description'        => $sensor->description,
                'display_sort_order' => $sensor->display_sort_order ?? 0,
                'required'           => $sensor->required,
                'min_value'          => $sensor->min_value,
                'max_value'          => $sensor->max_value,
                'unit_type'          => $sensor->unit_type,
                'data_type'          => $sensor->data_type,
            ];
        }
    }

    protected function clearSensors(): void
    {
        $this->sensors = [];
    }
}

<?php

namespace App\Livewire\Devices;

use App\Enums\DataType;
use App\Models\Device;
use Livewire\Component;

class CodeSnippetModal extends Component
{
    public int $deviceId;

    public string $deviceName = '';
    public string $jsonBodyString = '';
    public string $variablesString = '';

    protected $listeners = [
        'open-code-snippet' => 'open',
    ];

    public function open(int $deviceId): void
    {
        $this->deviceId = $deviceId;

        $device = Device::find($deviceId);
        if ($device) {
            $this->deviceName = $device->name;
        }

        $this->createJsonBodyForDevice();

        $this->dispatch('bs-modal-open', id: 'codeSnippetModal');
    }

    public function createJsonBodyForDevice(): void
    {
        $device = Device::find($this->deviceId);

        if (!$device) {
            return;
        }

        $varLines = [];
        $jsonParts = [];

        foreach ($device->sensors as $sensor) {
            $cppType = match ($sensor->data_type) {
                DataType::INTEGER->value => 'int',
                DataType::FLOAT->value   => 'float',
                default                  => 'String',
            };

            $varDefault = match ($cppType) {
                'int'   => '0',
                'float' => '0.0',
                default => '""',
            };

            $words = preg_split('/[\s_\-]+/', $sensor->name);
            $camelName = lcfirst(implode('', array_map('ucfirst', array_map('strtolower', $words))));
            $camelName = preg_replace('/[^a-zA-Z0-9]/', '', $camelName);

            $varLines[] = $cppType . ' ' . $camelName . 'Value = ' . $varDefault . ';  // ' . $sensor->name;

            if ($cppType === 'String') {
                $jsonParts[] = '\\"' . $sensor->key . '\\":\\"" + ' . $camelName . 'Value + "\\"';
            }
            else {
                $jsonParts[] = '\\"' . $sensor->key . '\\":" + String(' . $camelName . 'Value) + "';
            }
        }

        $this->variablesString = implode("\n", $varLines);
        $this->jsonBodyString = 'String jsonBody = "{' . implode(',', $jsonParts) . '}";';
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.devices.code-snippet-modal');
    }
}

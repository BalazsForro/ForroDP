<?php

namespace App\Livewire\Devices;

use App\Enums\DataType;
use App\Models\Device;
use Livewire\Component;

class CodeSnippetModal extends Component
{
    public int $deviceId;

    public string $deviceName = '';
    public string $snippetContent = '';

    protected $listeners = [
        'open-code-snippet' => 'open',
    ];

    public function open(int $deviceId): void
    {
        $this->reset();
        $this->resetValidation();

        $this->deviceId = $deviceId;

        $device = Device::with(['deviceType.codeSnippet', 'sensors'])->find($deviceId);

        if (!$device) {
            return;
        }

        $this->deviceName = $device->name;

        $snippet = $device->deviceType?->codeSnippet;

        if ($snippet) {
            $this->snippetContent = $this->resolvePlaceholders($snippet->content, $device);
        }

        $this->dispatch('bs-modal-open', id: 'codeSnippetModal');
    }

    private function resolvePlaceholders(string $content, Device $device): string
    {
        $serverUrl   = route('api.device.set.data');
        $variables   = $this->buildVariablesString($device);
        $jsonBody    = $this->buildJsonBodyString($device);

        return str_replace(
            ['{{SERVER_URL}}', '{{VARIABLES}}', '{{JSON_BODY}}'],
            [$serverUrl, $variables, $jsonBody],
            $content,
        );
    }

    private function buildVariablesString(Device $device): string
    {
        $lines = [];

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

            $camelName = $this->toCamelCase($sensor->name);

            $lines[] = $cppType . ' ' . $camelName . 'Value = ' . $varDefault . ';  // ' . $sensor->name;
        }

        return implode("\n", $lines);
    }

    private function buildJsonBodyString(Device $device): string
    {
        $jsonParts = [];

        foreach ($device->sensors as $sensor) {
            $cppType   = match ($sensor->data_type) {
                DataType::INTEGER->value => 'int',
                DataType::FLOAT->value   => 'float',
                default                  => 'String',
            };
            $camelName = $this->toCamelCase($sensor->name);

            if ($cppType === 'String') {
                $jsonParts[] = '\\"' . $sensor->key . '\\":\\"" + ' . $camelName . 'Value + "\\"';
            } else {
                $jsonParts[] = '\\"' . $sensor->key . '\\":" + String(' . $camelName . 'Value) + "';
            }
        }

        return 'String jsonBody = "{' . implode(',', $jsonParts) . '}";';
    }

    private function toCamelCase(string $name): string
    {
        $words = preg_split('/[\s_\-]+/', $name);
        $camel = lcfirst(implode('', array_map('ucfirst', array_map('strtolower', $words))));

        return preg_replace('/[^a-zA-Z0-9]/', '', $camel);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.devices.code-snippet-modal');
    }
}
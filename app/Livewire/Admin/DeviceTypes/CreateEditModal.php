<?php

namespace App\Livewire\Admin\DeviceTypes;

use App\Models\CodeSnippet;
use App\Models\DeviceType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateEditModal extends Component
{
    public ?DeviceType $deviceType = null;

    public string $name = '';
    public string $icon = '';
    public ?int $codeSnippetId = null;

    protected $listeners = [
        'open-device-type-create' => 'open',
        'open-device-type-edit'   => 'open',
    ];

    public function render(): Factory|View
    {
        return view('livewire.admin.device-types.create-edit-modal', [
            'codeSnippets' => CodeSnippet::orderBy('id')->get(),
        ]);
    }

    public function open(?int $deviceTypeId = null): void
    {
        $this->reset();
        $this->resetValidation();

        if ($deviceTypeId) {
            $this->deviceType    = DeviceType::findOrFail($deviceTypeId);
            $this->name          = $this->deviceType->name;
            $this->icon          = $this->deviceType->icon ?? '';
            $this->codeSnippetId = $this->deviceType->code_snippet_id;
        }

        $this->dispatch('bs-modal-open', id: 'deviceTypeModal');
    }

    public function save(): void
    {
        $this->validate([
            'name'          => 'required|string|max:45|unique:device_types,name',
            'icon'          => 'nullable|string|max:50',
            'codeSnippetId' => 'nullable|integer|exists:code_snippets,id',
        ]);

        DeviceType::create([
            'name'            => $this->name,
            'icon'            => $this->icon ?: null,
            'code_snippet_id' => $this->codeSnippetId,
        ]);

        $this->dispatch('bs-modal-close', id: 'deviceTypeModal');
        $this->dispatch('device-type-saved');
        $this->dispatch('bs-toast-show', message: 'Device type created.');
    }

    public function update(): void
    {
        $this->validate([
            'name'          => 'required|string|max:45|unique:device_types,name,' . $this->deviceType->id,
            'icon'          => 'nullable|string|max:50',
            'codeSnippetId' => 'nullable|integer|exists:code_snippets,id',
        ]);

        $this->deviceType->update([
            'name'            => $this->name,
            'icon'            => $this->icon ?: null,
            'code_snippet_id' => $this->codeSnippetId,
        ]);

        $this->dispatch('bs-modal-close', id: 'deviceTypeModal');
        $this->dispatch('device-type-saved');
        $this->dispatch('bs-toast-show', message: 'Device type updated.');
    }
}
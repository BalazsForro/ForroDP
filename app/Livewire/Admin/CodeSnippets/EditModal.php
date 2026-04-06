<?php

namespace App\Livewire\Admin\CodeSnippets;

use App\Models\CodeSnippet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditModal extends Component
{
    public ?CodeSnippet $snippet = null;

    public string $content = '';

    protected $listeners = [
        'open-code-snippet-edit' => 'open',
    ];

    public function render(): Factory|View
    {
        return view('livewire.admin.code-snippets.edit-modal');
    }

    public function open(int $snippetId): void
    {
        $this->reset();
        $this->resetValidation();

        $this->snippet = CodeSnippet::with('deviceType')->findOrFail($snippetId);
        $this->content = $this->snippet->content;

        $this->dispatch('bs-modal-open', id: 'codeSnippetEditModal');
    }

    public function save(): void
    {
        $this->validate([
            'content' => 'required|string',
        ]);

        $this->snippet->update(['content' => $this->content]);

        $this->dispatch('bs-modal-close', id: 'codeSnippetEditModal');
        $this->dispatch('code-snippet-saved');
        $this->dispatch('bs-toast-show', message: 'Code snippet updated.');
    }
}
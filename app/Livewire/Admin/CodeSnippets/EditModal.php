<?php

namespace App\Livewire\Admin\CodeSnippets;

use App\Models\CodeSnippet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditModal extends Component
{
    public ?CodeSnippet $snippet = null;

    public string $name = '';
    public string $content = '';

    protected $listeners = [
        'open-code-snippet-create' => 'open',
        'open-code-snippet-edit'   => 'open',
    ];

    public function render(): Factory|View
    {
        return view('livewire.admin.code-snippets.edit-modal');
    }

    public function open(?int $snippetId = null): void
    {
        $this->reset();
        $this->resetValidation();

        if ($snippetId) {
            $this->snippet = CodeSnippet::with('deviceType')->findOrFail($snippetId);
            $this->name    = $this->snippet->name;
            $this->content = $this->snippet->content;
        }

        $this->dispatch('bs-modal-open', id: 'codeSnippetEditModal');
    }

    public function save(): void
    {
        $this->validate([
            'name'    => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        if ($this->snippet) {
            $this->snippet->update([
                'name'    => $this->name,
                'content' => $this->content,
            ]);
            $message = 'Code snippet updated.';
        } else {
            CodeSnippet::create([
                'name'    => $this->name,
                'content' => $this->content,
            ]);
            $message = 'Code snippet created.';
        }

        $this->dispatch('bs-modal-close', id: 'codeSnippetEditModal');
        $this->dispatch('code-snippet-saved');
        $this->dispatch('bs-toast-show', message: $message);
    }
}
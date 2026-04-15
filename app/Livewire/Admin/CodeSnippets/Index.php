<?php

namespace App\Livewire\Admin\CodeSnippets;

use App\Models\CodeSnippet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    public Collection $snippets;

    protected $listeners = [
        'code-snippet-saved' => 'loadSnippets',
    ];

    public function mount(): void
    {
        $this->loadSnippets();
    }

    public function render(): Factory|View
    {
        return view('livewire.admin.code-snippets.index');
    }

    public function loadSnippets(): void
    {
        $this->snippets = CodeSnippet::with('deviceType')->orderBy('id')->get();
    }

    public function deleteSnippet(int $snippetId): void
    {
        CodeSnippet::findOrFail($snippetId)->delete();
        $this->loadSnippets();
    }
}

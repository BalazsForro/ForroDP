<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Profile extends Component
{
    // ── Profile info ─────────────────────────────────────────────────────────
    #[Validate('required|string|min:2|max:50')]
    public string $name  = '';

    public string $email = '';

    // ── Password change ───────────────────────────────────────────────────────

    #[Validate('required|string')]
    public string $current_password           = '';

    #[Validate('required|string|confirmed:new_password_confirmation|min:8')]
    public string $new_password               = '';

    #[Validate('required')]
    public string $new_password_confirmation  = '';

    public function mount(): void
    {
        $this->name  = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function saveProfile(): void
    {
        $this->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())],
        ]);

        auth()->user()->update([
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('bs-toast-show', message: 'Profile updated successfully.');
    }

    public function savePassword(): void
    {
        $this->validate();

        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->dispatch('bs-toast-show', message: 'Password changed successfully.');
    }

    public function render()
    {
        return view('livewire.user.profile');
    }
}

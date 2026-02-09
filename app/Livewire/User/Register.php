<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Register extends Component
{
    public User $user;

    public ?string $name = null;
    public ?string $password = null;
    public ?string $password_confirmation = null;
    public ?string $email = null;

    public function register(): void
    {
        $this->validate([
            'name'     => 'required|min:2|max:50',
            'password' => 'required|confirmed:password_confirmation|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'email'    => 'required|email|unique:users',
        ]);

        $this->user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->user->assignRole(Role::USER);

        Auth::login($this->user);

        $this->redirect(route('dashboard'));
    }

    public function render(): Factory|View
    {
        return view('livewire.user.register');
    }
}

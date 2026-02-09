<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Login extends Component
{
    public ?string $email = null;
    public ?string $password = null;

    public bool $remember = true;

    public function mount(): void
    {
        $this->email = config('app.users.localAdmin.email');
        $this->password = config('app.users.localAdmin.password');
    }

    public function login(): void
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('loginError', "Wrong email or password.");
            return;
        }

        session()->regenerate();

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function render(): Factory|View
    {
        return view('livewire.user.login');
    }
}

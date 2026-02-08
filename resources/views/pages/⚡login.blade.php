<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
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
};
?>

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-4 fw-bold">Sign in</h3>

                @error('loginError')
                <div class="alert alert-danger py-2 small">{{ $message }}</div>
                @enderror
                <form wire:submit.prevent="login" novalidate>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input
                            type="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="you@example.com"
                            wire:model.defer="email"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            wire:model.defer="password"
                            autocomplete="current-password"
                            required
                        >
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember me -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember"
                                wire:model="remember"
                            >
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <a href="#" class="small text-decoration-none">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Login</span>
                        <span wire:loading>Signing in…</span>
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-muted small mt-3">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</div>

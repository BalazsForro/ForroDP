
<div class="vh-100 d-flex align-items-center justify-content-center">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow border-1">
            <div class="card-body p-4">
                <h3 class="text-center mb-4 fw-bold">Create account</h3>

                <form wire:submit.prevent="register" novalidate>
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input
                            type="text"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="John Doe"
                            wire:model.defer="name"
                            autocomplete="name"
                            required
                        >
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                            autocomplete="new-password"
                            required
                        >
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password confirmation -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            Confirm password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="••••••••"
                            wire:model.defer="password_confirmation"
                            autocomplete="new-password"
                            required
                        >
                        @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Register</span>
                        <span wire:loading>Creating account…</span>
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-muted small mt-3">
            Already have an account?
            <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a>
        </p>

        <p class="text-center text-muted small mt-2">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</div>

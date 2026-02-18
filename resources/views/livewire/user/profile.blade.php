<div>

    {{-- ── Page header ─────────────────────────────────────────────────────── --}}
    <div class="rounded-3 border bg-light p-4 mb-4 d-flex align-items-center gap-4 flex-wrap">
        <div class="rounded-circle bg-secondary bg-opacity-10 border d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:72px;height:72px;">
            <i class="bi bi-person text-secondary" style="font-size:2rem;"></i>
        </div>
        <div>
            <h2 class="mb-1 fw-bold">{{ auth()->user()->name }}</h2>
            <div class="text-muted small">{{ auth()->user()->email }}</div>
            <div class="mt-2">
                @if(auth()->user()->hasRole(\App\Enums\Role::ADMIN->value))
                    <span class="badge text-bg-warning">Admin</span>
                @else
                    <span class="badge text-bg-secondary">User</span>
                @endif
                <span class="badge text-bg-light border text-muted ms-1">
                    Member since {{ auth()->user()->created_at->format('M Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Profile information ─────────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-1">Profile Information</h5>
                    <p class="text-muted small mb-4">Update your name and email address.</p>

                    <form wire:submit.prevent="saveProfile" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                wire:model="name"
                                autocomplete="name"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email address</label>
                            <input
                                type="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                wire:model="email"
                                autocomplete="email"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveProfile">
                            <span wire:loading.remove wire:target="saveProfile">Save changes</span>
                            <span wire:loading wire:target="saveProfile">Saving…</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Change password ─────────────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-1">Change Password</h5>
                    <p class="text-muted small mb-4">Use a strong password with uppercase, lowercase and a number.</p>

                    <form wire:submit.prevent="savePassword" novalidate>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current password</label>
                            <input
                                type="password"
                                id="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                wire:model="current_password"
                                autocomplete="current-password"
                                placeholder="••••••••"
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New password</label>
                            <input
                                type="password"
                                id="new_password"
                                class="form-control @error('new_password') is-invalid @enderror"
                                wire:model="new_password"
                                autocomplete="new-password"
                                placeholder="••••••••"
                            >
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">Confirm new password</label>
                            <input
                                type="password"
                                id="new_password_confirmation"
                                class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                wire:model="new_password_confirmation"
                                autocomplete="new-password"
                                placeholder="••••••••"
                            >
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="savePassword">
                            <span wire:loading.remove wire:target="savePassword">Update password</span>
                            <span wire:loading wire:target="savePassword">Updating…</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
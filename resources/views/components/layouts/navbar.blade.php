<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        <a class="navbar-brand" href="/">{{ config('app.name') }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav">
                @auth()
                    <li class="nav-item">
                        <a @class(['nav-link', 'active' => request()->routeIs('dashboard')]) href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a @class(['nav-link', 'active' => request()->routeIs('devices')]) href="{{ route('devices') }}">Devices</a>
                    </li>
                    @isAdmin()
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1 @if(request()->routeIs('admin.*')) active @endif"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                               style="color: #92400e;"
                            >
                                <i class="bi bi-shield-lock-fill" style="font-size: 0.8rem;"></i>
                                Admin
                            </a>
                            <ul class="dropdown-menu" style="min-width: 200px;">
                                <li>
                                    <span class="dropdown-item-text small text-muted d-flex align-items-center gap-1" style="font-size: 0.7rem; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 600;">
                                        <i class="bi bi-shield-lock"></i> Admin Panel
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item @if(request()->routeIs('admin.device-types')) active @endif"
                                       href="{{ route('admin.device-types') }}">
                                        <i class="bi bi-cpu me-2 text-muted"></i>Device Types
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item @if(request()->routeIs('admin.code-snippets')) active @endif"
                                       href="{{ route('admin.code-snippets') }}">
                                        <i class="bi bi-code-slash me-2 text-muted"></i>Code Snippets
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endisAdmin
                @endauth
            </ul>

            @auth()
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle bg-secondary bg-opacity-10 border d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                <i class="bi bi-person text-secondary"></i>
                            </span>
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->hasRole(App\Enums\Role::ADMIN->value))
                                <span class="badge text-bg-warning d-none d-lg-inline" style="font-size:0.65rem;">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item @if(request()->routeIs('profile')) active @endif" href="{{ route('profile') }}">
                                    <i class="bi bi-person-gear me-2"></i>Edit Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            @else
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a @class(['nav-link', 'active' => request()->routeIs('login')]) href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a @class(['nav-link', 'active' => request()->routeIs('register')]) href="{{ route('register') }}">Register</a>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

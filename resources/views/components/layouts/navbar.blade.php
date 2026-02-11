<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        <a class="navbar-brand" href="/">{{ config('app.name') }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul @class(['navbar-nav', 'ms-auto' => !auth()->check()])>
                @auth()
                    <li class="nav-item">
                        <a @class([
                            'nav-link',
                            'active' => request()->routeIs('dashboard')
                        ]) href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a @class([
                            'nav-link',
                            'active' => request()->routeIs('devices')
                        ]) href="/devices">Devices</a>

                    <li class="nav-item">
                        {{--note replace lated--}}
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a @class([
                            'nav-link',
                            'active' => request()->routeIs('login')
                        ])  href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a @class([
                            'nav-link',
                            'active' => request()->routeIs('register')
                        ])  href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
        <div class="d-flex align-items-center">
            @if(auth()?->user()?->hasRole(App\Enums\Role::ADMIN->value))
                <p class="text-muted mb-0">Admin</p>
            @else
                <p class="text-muted mb-0">User</p>
            @endif
        </div>
    </div>
</nav>

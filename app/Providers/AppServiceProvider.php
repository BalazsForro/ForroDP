<?php

namespace App\Providers;

use App\Enums\Role;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('isAdmin', function (): bool {
            return auth()->check() && auth()->user()->hasRole(Role::ADMIN->value);
        });
    }
}

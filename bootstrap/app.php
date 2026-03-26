<?php

use App\Http\Middleware\DeviceTokenAuth;
use App\Models\DeviceToken;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('dashboard');
        });

        $middleware->alias([
            'device.token' => DeviceTokenAuth::class,
            'role'         => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

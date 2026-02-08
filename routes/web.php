<?php

use App\Enums\Role;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::dashboard')->name('dashboard');

Route::middleware(['guest'])
    ->group(function () {
        Route::livewire('/login', 'pages::login')->name('login');
        Route::livewire('/register', 'pages::register')->name('register');
    });

Route::middleware(['auth'])
    ->group(function () {
        Route::post('/logout', function () {
            auth()->logout();
            return redirect()->route('login');
        })->name('logout');
    });

Route::middleware(['auth', Role::ADMIN->getMiddleware()])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    });

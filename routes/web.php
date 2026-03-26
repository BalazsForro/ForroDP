<?php

use App\Enums\Role;
use App\Livewire\Admin\DeviceTypes\Index as AdminDeviceTypesIndex;
use App\Livewire\Dashboard;
use App\Livewire\Devices\Index as DevicesIndex;
use App\Livewire\User\Login;
use App\Livewire\User\Profile;
use App\Livewire\User\Register;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');

Route::middleware(['guest'])
    ->group(function () {
        Route::get('/login', Login::class)->name('login');
        Route::get('/register', Register::class)->name('register');
    });

Route::middleware(['auth'])
    ->group(function () {
        Route::post('/logout', function () {
            auth()->logout();
            session()->invalidate();
            return redirect()->route('login');
        })->name('logout');

        Route::get('/devices', DevicesIndex::class)->name('devices');
        Route::get('/profile', Profile::class)->name('profile');
    });

Route::middleware(['auth', Role::ADMIN->getMiddleware()])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/device-types', AdminDeviceTypesIndex::class)->name('device-types');
    });

<?php

use App\Enums\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])
    ->group(function () {

    });

Route::middleware(['auth', Role::ADMIN->getMiddleware()])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    });

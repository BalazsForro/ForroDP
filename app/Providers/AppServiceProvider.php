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

        Blade::if('isOwner', function ($device): bool {
            return auth()->check() && $device->owner->id == auth()->user()->id;
        });

        Blade::if('isShared', function ($device): bool {
            return auth()->check() && $device->sharedUsers->contains(auth()->user());
        });

        Blade::if('canRead', function ($device): bool {

            $isAdmin = auth()->user()->hasRole(Role::ADMIN->value);

            if ($isAdmin) {
                return true;
            }

            $isOwner = $device->owner->id == auth()->id();

            if ($isOwner) {
                return true;
            }

            $shareSettings = $device->shares->where('shared_with_user_id', auth()->id())->first();

            if (!$shareSettings) {
                return false;
            }

            return $shareSettings->canRead();
        });

        Blade::if('canWrite', function ($device): bool {

            $isAdmin = auth()->user()->hasRole(Role::ADMIN->value);

            if ($isAdmin) {
                return true;
            }


            $isOwner = $device->owner->id == auth()->id();

            if ($isOwner) {
                return true;
            }

            $shareSettings = $device->shares->where('shared_with_user_id', auth()->id())->first();

            if (!$shareSettings) {
                return false;
            }

            return $shareSettings->canWrite();
        });

        Blade::if('isActive', function ($device): bool {
            return !$device->trashed();
        });
    }
}

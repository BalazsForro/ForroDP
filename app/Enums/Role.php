<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case USER  = 'user';

    public function getMiddleware(): string
    {
        return 'role:' . $this->value;
    }

    public function getDisplayName($id): string
    {
        return match ($id) {
            1 => 'Admin',
            2 => 'User',
        };
    }
}

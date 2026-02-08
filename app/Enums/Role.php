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
}

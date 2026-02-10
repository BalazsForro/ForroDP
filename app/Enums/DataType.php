<?php

namespace App\Enums;

enum DataType: int
{
    case INTEGER = 1;
    case FLOAT   = 2;

    public function getDisplayName(): string
    {
        return match ($this) {
            self::INTEGER => 'Integer',
            self::FLOAT   => 'Float',
        };
    }
}

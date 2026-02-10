<?php

namespace App\Enums;

enum DeviceType: int
{
    case ARDUINO      = 1;
    case ESP32        = 2;
    case RASPBERRY_PI = 3;
    case OTHER        = 4;

    public function getDisplayName(): string
    {
        return match ($this) {
            self::ARDUINO      => 'Arduino',
            self::ESP32        => 'ESP32',
            self::RASPBERRY_PI => 'Raspberry Pi',
            self::OTHER        => 'Other',
        };
    }
}

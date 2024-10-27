<?php

namespace App\Enums;

enum RoleEnum : int
{
    // Admin Dashboard
    case SUPER_ADMIN = 1;


    public function lang(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => trns('super_admin'),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'super_admin',
        };
    }
}

<?php

namespace App\Enums;

enum GlobalStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';



    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'ACTIVE',
            self::INACTIVE => 'INACTIVE',
        };
    }
}

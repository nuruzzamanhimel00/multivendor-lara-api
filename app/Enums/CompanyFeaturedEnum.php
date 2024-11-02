<?php

namespace App\Enums;

enum CompanyFeaturedEnum: int
{
    case Yes = 1;
    case No = 0;



    public function label(): string
    {
        return match ($this) {
            self::Yes => 'Yes',
            self::No => 'No',
        };
    }
}

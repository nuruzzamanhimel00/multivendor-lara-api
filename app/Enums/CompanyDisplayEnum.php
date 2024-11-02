<?php

namespace App\Enums;

enum CompanyDisplayEnum: int
{

    case Yes = 1;
    case No = 0;


    public function Display_label(): string
    {
        return match ($this) {
            self::Yes => 'Yes',
            self::No => 'No',
        };
    }
}

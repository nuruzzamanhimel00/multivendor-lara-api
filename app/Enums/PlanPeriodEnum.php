<?php

namespace App\Enums;

enum PlanPeriodEnum: string
{
    case MONTHLY = 'monthly';
    case ANNUALLY = 'annually';



    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'Monthly',
            self::ANNUALLY => 'Annually',
        };
    }
}

<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case PAID = 'paid';
    case REFUND = 'refund';



    public function label(): string
    {
        return match ($this) {
            self::PAID => 'PAID',
            self::REFUND => 'REFUND',
        };
    }
}

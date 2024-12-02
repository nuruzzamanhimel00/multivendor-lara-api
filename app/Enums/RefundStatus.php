<?php

namespace App\Enums;

enum RefundStatus: string
{
    case PENDING = 'pending';
    case APPROVE = 'approved';
    case REJECT = 'rejected';



    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'PENDING',
            self::APPROVE => 'APPROVE',
            self::REJECT => 'REJECT',
        };
    }
}

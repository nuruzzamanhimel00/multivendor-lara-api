<?php

namespace App\Models;

use App\Enums\RefundStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanPurchaseRefund extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => RefundStatus::class,
    ];
}

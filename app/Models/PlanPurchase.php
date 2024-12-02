<?php

namespace App\Models;

use App\Enums\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanPurchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => GlobalStatus::class,
    ];
}

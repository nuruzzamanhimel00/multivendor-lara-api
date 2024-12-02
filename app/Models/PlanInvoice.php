<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanInvoice extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => InvoiceStatus::class,
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'limit_items',
        'limit_orders',
        'price',
        'period',
        'plan_description',
        'plan_features',
        'limit_views',
        'enable_orders',
    ];
}

<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'company_id',
        'document',
        'status',
        'status_date',
        'end_date',
        'price',
        'created_by',
        'updated_by',
        'plan_id',
        'user_plan_id'
    ];

    public static function booted()
    {
        static::creating(function($model){
            $model->created_by = Auth::id() ?? null;
        });

        static::updating(function($model){
            $model->updated_by = Auth::id() ?? null;
        });

    }

    const PENDING = 'Pending';
    const ACCEPTED = 'Accepted';
    const REJECTED = 'Rejected';
}

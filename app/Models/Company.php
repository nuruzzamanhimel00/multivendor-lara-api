<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'subdomain',
        'shop_name',
        'shop_description',
        'shop_phone',
        'shop_address',
        'shop_logo',
        'shop_image',
        'cover_image',
        'lat',
        'lng',
        'is_featured',
        'display_product',
        'views',
        'payment_info',
    ];
    public const FILE_LOGO_PATH = 'company/logo';
    public const FILE_IMAGE_PATH = 'company/image';
    public const FILE_COVER_IMAGE_PATH = 'company/cover_image';

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}

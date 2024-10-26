<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

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
        'plan_id',
        'user_plan_id'
    ];
    protected $appends = ['company_logo_url','company_image_url','company_cover_image_url','created_date'];
    public const FILE_LOGO_PATH = 'company/logo';
    public const FILE_IMAGE_PATH = 'company/image';
    public const FILE_COVER_IMAGE_PATH = 'company/cover_image';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }


    public function getCompanyLogoUrlAttribute()
    {
        return getStorageImage(self::FILE_LOGO_PATH, $this->shop_logo, false);
    }
    public function getCompanyImageUrlAttribute()
    {
        return getStorageImage(self::FILE_IMAGE_PATH, $this->shop_image, false);
    }
    public function getCompanyCoverImageUrlAttribute()
    {
        return getStorageImage(self::FILE_COVER_IMAGE_PATH, $this->cover_image, false);
    }

    public function getCreatedDateAttribute(){

        return !is_null($this->created_at) ? $this->created_at->diffForHumans(): "N/A";
    }

    //relationships
    public function currentPlan(){

        return $this->belongsTo(Plan::class,'plan_id','id');
    }
    public function currentUserPlan(){

        return $this->belongsTo(Plan::class,'user_plan_id','id');
    }

}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    const USER_TYPE_ADMIN = 'admin';
    const USER_TYPE_SELLER = 'owner';
    const USER_TYPE_USER = 'user';

    const ADMIN_USER_TYPES = [self::USER_TYPE_ADMIN,self::USER_TYPE_SELLER];

    const FILE_STORE_PATH ='users';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'user_type',
        'last_login_date',
        'phone',
        'status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar_url','last_login_dt'];

    public function getAvatarUrlAttribute()
    {
        return getStorageImage(self::FILE_STORE_PATH, $this->avatar, true);
    }
    public function scopeIsOwner(Builder $builder)
    {
        return $builder->where('user_type', User::USER_TYPE_SELLER);
    }
    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('status', User::STATUS_ACTIVE);
    }
    public function getLastLoginDtAttribute(){

        return !is_null($this->last_login_date) ? $this->last_login_date->diffForHumans(): "N/A";
    }
    //relations
    public function company(){

        return $this->hasOne(Company::class,'user_id','id');
    }

}

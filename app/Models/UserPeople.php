<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPeople extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function login(){
        return $this->hasMany(login::class,'user_people_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens, HasRoles;

    protected  $primaryKey = "user_id";
    public  $timestamps = false;
    public function PropertiesCount(){
        return $this->hasMany('App\Models\Property','agent_id','user_id');
    }
}

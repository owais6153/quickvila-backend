<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\SendCodeByEmail;
use App\Notifications\Auth\SendCodeByPhone;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndAbilities;
    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone_verified_at',
        'phone',
        'address',
        'latitude',
        'longitude',
        'dob',
        'avatar',
        'identity_card',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'code'
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];
    public function getAvatarAttribute($attr){
        return validateImageUrl($attr);
    }
    public function sendCodeByEmail()
    {
        $this->notify(new SendCodeByEmail());
    }
    public function sendCodeByPhone()
    {
        $this->notify(new SendCodeByPhone());
    }

    // Relations
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id', 'id');
    }
    public function codes()
    {
        return $this->hasMany(UserCode::class, 'user_id', 'id');
    }
}

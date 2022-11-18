<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendCode;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndAbilities, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone_verified_at',
        'address',
        'dob',
        'avatar',
        'identity_card'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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

    //Emails
    public function sendCodeByEmail()
    {
        $this->notify(new SendCode());
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'email',
        'phone',
        'address1',
        'address2',
        'latitude',
        'longitude',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

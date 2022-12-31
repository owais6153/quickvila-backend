<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCode extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'code',
        'user_id',
        'type',
    ];
}

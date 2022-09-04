<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public $fillable = [
        'total',
        'count',
        'user_id'
    ];
    public function items()
    {
        return $this->hasMany(CartProduct::class, 'cart_id', 'id');
    }
}

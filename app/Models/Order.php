<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $fillable = [
        'count',
        'tax',
        'delivery_charges',
        'total',
        'latitude',
        'longitude',
        'status',
        'user_id',
        'address'
    ];
    public function items()
    {
        return $this->hasMany(CartProduct::class, 'cart_id', 'id');
    }

}

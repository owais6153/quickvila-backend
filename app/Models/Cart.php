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
        'user_id',
        'identifier',
        'ip',
        'sub_total',
        'delivery_charges',
        'tax',
        'platform_charges'
    ];
    public function items()
    {
        return $this->hasMany(CartProduct::class, 'cart_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

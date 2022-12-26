<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'count',
        'sub_total',
        'platform_charges',
        'delivery_charges',
        'tax',
        'total',
        'status',
        'customer_id',
        'check_for_refunds',
        'note'
    ];
    public function items()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

}

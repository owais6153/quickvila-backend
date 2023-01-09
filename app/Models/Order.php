<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
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
        'user_id',
        'status',
        'prescription',
        'check_for_refunds',
        'order_no',
        'note',
        'tip',
        'payment_id'
    ];
    public function getCreatedAtAttribute($attr){
        return Carbon::parse($attr)->diffForHumans();
    }
    public function items()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}

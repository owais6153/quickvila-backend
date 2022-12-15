<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    use HasFactory;
    public $fillable = [
        'line_total',
        'qty',
        'cart_id',
        'product_id',
        'variations_id'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variations_id', 'id');
    }
}

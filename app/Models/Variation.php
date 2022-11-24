<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_id',
        'price',
        'sale_price',
        'variants',
    ];
    public function getVariantsAttribute($attr)
    {
        return json_decode($attr);
    }
}

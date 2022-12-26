<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    use HasFactory;
    public $fillable = [
        'radius',
        'price',
        'price_condition',
        'store_id',
    ];

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
}

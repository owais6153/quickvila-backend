<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_required',
        'product_id',
        'type'
    ];

    public function options(){
        return $this->hasMany(VariationOption::class, 'variation_id', 'id');
    }
}

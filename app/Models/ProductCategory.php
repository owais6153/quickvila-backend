<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'name',
        'user_id',
        'store_id'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'pivot_categories', 'category_id', 'pivot_id')->withPivot('type')->wherePivot('type', 'product');
    }
}

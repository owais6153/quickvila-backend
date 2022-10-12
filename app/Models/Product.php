<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'description',
        'short_description',
        'store_id',
        'image',
        'manage_able',
        'is_featured',
        'user_id',
        'price',
        'sale_price'
    ];

    public function getImageAttribute($attr){
        return (strpos($attr, 'http') !== false || $attr == null) ? $attr : env('FILE_URL') . $attr;
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'pivot_categories', 'pivot_id', 'category_id')->withPivot('type')->wherePivot('type', 'product');
    }
    public function author()
    {
        return $this->belongsTo(User::class);
    }
    public function variations(){
        return $this->hasMany(Variation::class, 'product_id', 'id');
    }
}

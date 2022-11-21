<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, softDeletes;
    public $fillable = [
        'name',
        'description',
        'short_description',
        'store_id',
        'image',
        'manage_able',
        'user_id',
        'price',
        'sale_price',
        'product_type',
        'product_id',
        'is_site_featured',
        'is_store_featured',
        'status',
        'is_tax_free',
    ];

    public function getImageAttribute($attr)
    {
        return validateImageUrl($attr);
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
    public function variations()
    {
        return $this->hasMany(Variation::class, 'product_id', 'id');
    }
}

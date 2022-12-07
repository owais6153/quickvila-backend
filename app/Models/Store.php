<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;
    public $fillable = [
        'name',
        'logo',
        'cover',
        'description',
        'url',
        'address',
        'latitude',
        'longitude',
        'manage_able',
        'user_id',
        'owner_id',
        'status',
        'type',
        'is_featured'
    ];
    public function getLogoAttribute($attr){
        return validateImageUrl($attr);
    }
    public function getCoverAttribute($attr){
        return validateImageUrl($attr);
    }

    public function categories()
    {
        return $this->belongsToMany(StoreCategory::class, 'pivot_categories', 'pivot_id', 'category_id')->withPivot('type')->wherePivot('type', 'store');
    }
    public function author()
    {
        return $this->belongsTo(User::class);
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
    public function setting(){
        return $this->hasOne(StoreSetting::class, 'store_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'store_id', 'id');
    }
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'store_id', 'id');
    }
}

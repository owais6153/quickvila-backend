<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Casts\Image;

class Store extends Model
{
    use HasFactory;
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
        'user_id'
    ];
    protected $casts = [
        'logo' => Image::class,
        'cover' => Image::class,
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function categories()
    {
        return $this->belongsToMany(StoreCategory::class, 'pivot_categories', 'pivot_id', 'category_id')->withPivot('type')->wherePivot('type', 'store');
    }
}

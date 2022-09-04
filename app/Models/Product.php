<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Image;

class Product extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'description',
        'short_description',
        'store_id',
        'image',
    ];
    protected $casts = [
        'image' => Image::class
    ];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBanner extends Model
{
    use HasFactory;
    public $fillable = [
        'title',
        'subtitle',
        'action',
        'thumbnail',
        'store_id',
        'user_id'
    ];

    public function getImageAttribute($attr){
        return validateImageUrl($attr);
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}

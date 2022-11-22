<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory, SoftDeletes;
    public $fillable = [
        'product_id',
        'user_id',
        'store_id',
        'rating',
        'comment',
    ];
    public function getCreatedAtAttribute($attr){
        return Carbon::parse($attr)->diffForHumans();
     }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

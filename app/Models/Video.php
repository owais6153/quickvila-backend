<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Video extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'title',
        'video',
        'thumbnail',
        'sort',
    ];
    public function getThumbnailAttribute($attr){
        return validateImageUrl($attr);
    }
    public function getVideoAttribute($attr){
        validateVideoUrl($attr);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\File;


class Video extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'video',
        'thumbnail',
        'sort',
    ];
    public function getThumbnailAttribute($attr){
        return (strpos($attr, 'http') !== false || $attr == null) ? $attr : env('FILE_URL') . $attr;
    }
    public function getVideoAttribute($attr){
        return (strpos($attr, 'http') !== false || $attr == null) ? $attr : env('FILE_URL') . $attr;
    }

}

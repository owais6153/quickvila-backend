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
    protected $casts = [
        'thumbnail' => File::class,
        'video' => File::class
    ];
}

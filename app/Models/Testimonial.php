<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\File;

class Testimonial extends Model
{
    use HasFactory;
    public $fillable = [
        'title',
        'subtitle',
        'image',
        'description',
        'sort',
    ];
    protected $casts = [
        'image' => File::class
    ];
}

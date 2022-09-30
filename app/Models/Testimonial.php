<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function getImageAttribute($attr){
        return (strpos($attr, 'http') !== false || $attr == null) ? $attr : env('FILE_URL') . $attr;
    }
}

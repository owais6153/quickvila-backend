<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;
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

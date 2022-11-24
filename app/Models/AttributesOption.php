<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributesOption extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'media',
        'user_id',
        'store_id',
        'attr_id',
    ];
}

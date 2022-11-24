<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'type',
        'user_id',
        'store_id',
    ];
    public function options()
    {
        return $this->hasMany(AttributesOption::class, 'attr_id', 'id');
    }
}

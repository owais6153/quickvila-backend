<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WPCron extends Model
{
    use HasFactory;
    protected $table='wp_crons';
    public $fillable = [
        'posts_per_page',
        'page',
        'error',
    ];
}

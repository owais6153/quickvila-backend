<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronDetial extends Model
{
    use HasFactory;
    public $fillable = [
        'store_id',
        'totalPages',
        'currentPage',
        'traceId',
        'totalResources',
        'errors',
    ];
}

<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class File implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return (strpos($value, 'http') !== false || $value == null) ? $value : env('FILE_URL') . $value;
    }
    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}

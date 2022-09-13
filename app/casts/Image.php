<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Image implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return (strpos($value, 'http') !== false || $value == null) ? $value : 'http://localhost/trikaro/public/' . $value;
    }
    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}

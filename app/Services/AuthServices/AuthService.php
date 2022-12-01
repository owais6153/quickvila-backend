<?php

namespace App\Services\AuthServices;

use Auth;

class AuthService
{
    public function attempt(array $fields, bool $rememberme): bool
    {
        return Auth::attempt($fields, $rememberme);
    }
    public function logout()
    {
        Auth::logout();
    }
}

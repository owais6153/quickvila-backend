<?php

namespace App\Services\AuthServices;

use Illuminate\Http\Request;


class ApiAuthService extends AuthService
{
    public function authenticate(array $credentials, Request $request): bool
    {
        if ($this->attempt($credentials, request()->has('remember'))) {
            return true;
        }
        return false;
    }
}

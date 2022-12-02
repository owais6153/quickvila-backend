<?php

namespace App\Services\AuthServices;

use Illuminate\Http\Request;


class WebAuthService extends AuthService
{
    public function authenticate(array $credentials, Request $request): bool
    {
        if ($this->attempt($credentials, request()->has('remember'))) {
            $request->session()->regenerate();
            return true;
        }
        return false;
    }
    public function signout()
    {
        $this->logout();
    }
}

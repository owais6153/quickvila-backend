<?php

namespace App\Services\AuthServices;

use Illuminate\Http\Request;


class WebAuthService extends AuthService
{
    public function authenticate(array $credentials, Request $request): bool
    {
        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $fields = array($fieldType => $credentials['email'], 'password' => $credentials['password']);
        if ($this->attempt($fields, request()->has('remember'))) {
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

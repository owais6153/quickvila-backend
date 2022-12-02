<?php

namespace App\Services\AuthServices;

use Auth;

class AuthService
{
    public function attempt(array $credentials, bool $rememberme): bool
    {
        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $fields = array($fieldType => $credentials['email'], 'password' => $credentials['password']);

        return Auth::attempt($fields, $rememberme);
    }
    public function logout()
    {
        Auth::logout();
    }
}

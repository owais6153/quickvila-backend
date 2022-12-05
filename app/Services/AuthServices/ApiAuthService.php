<?php

namespace App\Services\AuthServices;

use Illuminate\Http\Request;
use App\Models\User;


class ApiAuthService extends AuthService
{
    public function authenticate(array $credentials, Request $request)
    {
        $type = $request->has('type') && $request->type == 'store' ? Store() : Customer();
        if ($this->attempt($credentials, request()->has('remember'))) {

            $user = User::where('email', $request->email)
            ->whereIs($type)
            ->first();

            if (!empty($user)) {
                return $user;
            }
        }
        return false;
    }
}

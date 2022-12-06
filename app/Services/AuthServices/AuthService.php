<?php

namespace App\Services\AuthServices;

use Auth;
use App\Models\User;
use App\Models\UserCode;
use Hash;

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
    public function register($request, $role)
    {
        $user = new User;
        $user->name = "$request->first_name $request->last_name";
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assign($role);
        return $user;
    }
    public function verifycode($code, $user_id)
    {
        $user_code = UserCode::where('code',  $request->code)->where('user_id', $user_id)->first();
        return $user_code;
    }
}

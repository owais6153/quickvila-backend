<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Auth;
use Hash;
use App\Models\User;
class AuthController extends Controller
{

    public function authenticate(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'required|exists:users,email|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                return response()->json($error,400);
            }
            $credentials = [];
            $credentials['email'] = $request->email;
            $credentials['password'] = $request->password;

            $fieldType =  'email';
            if (Auth::attempt(array($fieldType => $credentials['email'], 'password' => $credentials['password']), request()->has('remember') )) {
                $user = User::where('email', $request->email)->first();

                return response()->json([
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }

            $error['errors'] = ['login' => ['Credentials do not match our records.']];
            return response()->json($error, 404);
        }
        catch(\Throwable $th){
            $error['errors'] = ['error' => [$th->getMessage()]];
            return response()->json($error, 404);
        }
    }
}

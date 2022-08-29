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
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|exists:users,email|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }
            $credentials = [];
            $credentials['email'] = $request->email;
            $credentials['password'] = $request->password;

            $fieldType =  'email';
            if (Auth::attempt(array($fieldType => $credentials['email'], 'password' => $credentials['password']), request()->has('remember'))) {
                $user = User::where('email', $request->email)->first();

                return response()->json([
                    'status' => 200,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->getToken(env("API_TOKEN", "API TOKEN"))->plainTextToken
                ], 200);
            }

            $error['errors'] = ['login' => ['Credentials do not match our records.'], 'status' => 400];
            return response()->json($error, 404);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function register(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|exists:users,email|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }


            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            if (env('APP_ENV') == 'local')
                $user->email_verified_at = date("Y-m-d", time());

            $user->save();

            if (env('APP_ENV') != 'local')
                $user->sendEmailVerificationNotification();
            return response()->json([
                'status' => 200,
                'message' => 'User Register Successfully',
                'token' => $user->createToken(env("API_TOKEN", "API TOKEN"))->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function signout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}

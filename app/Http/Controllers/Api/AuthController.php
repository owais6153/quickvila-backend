<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Auth;
use Hash;
use Str;
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
                    'userId' => $user->id,
                    'verified' => $user->email_verified_at,
                    'status' => 200,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken(Str::random(30))->plainTextToken
                ], 200);
            }

            $error['errors'] = ['login' => ['Credentials do not match our records.']];
            $error['status'] = 400;
            return response()->json($error, 404);
        } catch (\Throwable $th) {
            $error['errors'] = ['Server error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function register(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
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
            $user->assign('Customer');

            if (env('APP_ENV') != 'local')
                $user->sendEmailVerificationNotification();

            return response()->json([
                'userId' => $user->id,
                'verified' => $user->email_verified_at,
                'status' => 200,
                'message' => 'User Register Successfully',
                'token' => $user->createToken(Str::random(30))->plainTextToken
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


        $data['status'] = 200;
        $data['message'] = 'Cart is empty';
        return  response()->json($data, 200);
    }
}

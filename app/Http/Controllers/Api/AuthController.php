<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Str;
use Config;
use App\Models\User;use Mail;


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
                    'user' => $user,
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
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                'phone' => 'required|numeric|min:10|unique:users,phone',
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
            $user->name = "$request->first_name $request->last_name";
            $user->first_name = $request->first_name ;
            $user->last_name = $request->last_name ;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->code = rand(100000, 999999);

            if(!!Config::get('mail.mailers.smtp.should_send') === true){
                Mail::send(array(), array(), function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject("Verify Your Account")
                        ->setBody("<p>Hi $user->name!<br>
                        Please verify your account.
                        </p><h3>Your Verification Code is : $user->code</h3>", 'text/html');
                });
            }

            $user->save();
            $user->assign('Customer');


            if (env('APP_ENV') != 'local')
                $user->sendEmailVerificationNotification();

            return response()->json([
                'userId' => $user->id,
                'user' => $user,
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
    public function verify(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'code' => 'required|min:6|max:6|exists:users,code',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;

                return response()->json($error, 400);
            }
            if ($request->user()->code == $request->code) {
                $user = $request->user();
                $user->update([
                    'email_verified_at' => date("Y-m-d", time()),
                    'code' => null
                ]);
                return response()->json([
                    'userId' => $user->id,
                    'user' => $user,
                    'verified' => $user->email_verified_at,
                    'status' => 200,
                    'message' => 'User verified Successfully',
                    'token' => $user->createToken(Str::random(30))->plainTextToken
                ], 200);
            }
            $error['errors'] = ['code' => ['Code is invalid']];
            $error['status'] = 500;
            return response()->json($error, 500);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function resend(Request $request)
    {
        try {
            $user = $request->user();
            $user->update([
                'code' => rand(100000, 999999)
            ]);


            if(!!Config::get('mail.mailers.smtp.should_send') === true){
                Mail::send(array(), array(), function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject("Verify Your Account")
                        ->setBody("<p>Hi $user->name!<br>
                        Please verify your account.
                        </p><h3>Your Verification Code is : $user->code</h3>", 'text/html');
                });
            }

            return response()->json([
                'status' => 200,
                'message' => 'Code resend successfully',
            ], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function forget(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;

                return response()->json($error, 400);
            }
            $user = User::where('email', $request->email)->first();
            $user->update([
                'code' => rand(100000, 999999)
            ]);

            if(!!Config::get('mail.mailers.smtp.should_send') === true){
                Mail::send(array(), array(), function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject("Verify Your Account")
                        ->setBody("<p>Hi $user->name!<br>
                        Please verify your account.
                        </p><h3>Your Verification Code is : $user->code</h3>", 'text/html');
                });
            }

            return response()->json([
                'status' => 200,
                'code' => $user->code,
                'update_token' => $user->createToken(Str::random(30))->plainTextToken,
                'message' => 'Code send to email',
            ], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function forgetCodeVerify(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'code' => 'required|min:6|max:6|exists:users,code',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;

                return response()->json($error, 400);
            }
            $user = $request->user();

            if ($user->code != $request->code) {
                $error['errors'] = ['code' => ['Code is invalid']];
                $error['status'] = 500;
                return response()->json($error, 500);
            }
            return response()->json([
                'status' => 200,
                'message' => 'User verified successfully',
            ], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function forgetUpdatePwd(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;

                return response()->json($error, 400);
            }
            $user = $request->user();
            if (!empty($user)) {
                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Password Updated',
                ], 200);
            }
            $error['errors'] = ['code' => ['Code is invalid']];
            $error['status'] = 500;
            return response()->json($error, 500);

            return response()->json([
                'status' => 200,
                'message' => 'User verified successfully',
            ], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }

}

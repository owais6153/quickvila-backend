<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Str;
use App\Models\User;
use App\Models\Store;
use App\Events\UserEvent;
use App\Models\UserCode;
use App\Services\AuthServices\ApiAuthService;

class AuthController extends Controller
{
    protected $setting = false;
    protected $service;
    function __construct()
    {
        $this->setting = getSetting('general');
        $this->service = new ApiAuthService();
    }

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
            $user = $this->service->authenticate($credentials, $request);

            if ($user !== false) {

                $data = [
                    'userId' => $user->id,
                    'user' => $user,
                    'verified' => $this->setting['default_verification_method'] == 'email' ? $user->email_verified_at : $user->phone_verified_at,
                    'status' => 200,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken(Str::random(30))->plainTextToken
                ];

                if ($request->has('type') && $request->type == 'store')
                    $data['mystore'] = Store::where('owner_id', $user->id)->first();

                return response()->json($data, 200);
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
                'address' => 'nullable|min:3',
                'latitude' => 'nullable|between:-90,90',
                'longitude' => 'nullable|between:-180,180',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $user = $this->service->register($request, Customer());


            event(new UserEvent($user));
            return response()->json([
                'userId' => $user->id,
                'user' => $user,
                'verified' => $this->setting['default_verification_method'] == 'email' ? $user->email_verified_at : $user->phone_verified_at,
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
                'code' => 'required|min:6|max:6|exists:user_codes,code',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;

                return response()->json($error, 400);
            }
            $user = $request->user();
            $user_code = $this->service->verifycode($request->code, $user->id);


            if (!empty($user_code)) {
                $user->update([
                    $user_code->type . "_verified_at" => date("Y-m-d", time()),
                ]);
                $user->codes()->delete();
                return response()->json([
                    'userId' => $user->id,
                    'user' => $user,
                    'verified' => $this->setting['default_verification_method'] == 'email' ? $user->email_verified_at : $user->phone_verified_at,
                    'status' => 200,
                    'message' => 'User verified Successfully',
                    'token' => $user->createToken(Str::random(30))->plainTextToken
                ], 200);
            }
            $error['errors'] = ['code' => ['Invalid Code']];
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
            event(new UserEvent($user));

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
            event(new UserEvent($user));

            return response()->json([
                'status' => 200,
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
                'code' => 'required|min:6|max:6|exists:user_codes,code',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;

                return response()->json($error, 400);
            }
            $user = $request->user();
            $user_code = $this->service->verifycode($request->code, $user->id);

            if (!empty($user_code)) {
                $user->update([
                    $user_code->type . "_verified_at" => date("Y-m-d", time()),
                ]);
                $user->codes()->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'User verified successfully',
                ], 200);
            }
            $error['errors'] = ['code' => ['Invalid Code']];
            $error['status'] = 500;
            return response()->json($error, 500);
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

<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    function __construct()
    {
        $this->setting = getSetting('general');
    }
    public function update(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'nickname' => 'nullable|min:3',
                'address' => 'nullable|min:3',
                'latitude' => 'nullable|between:-90,90',
                'longitude' => 'nullable|between:-180,180',
                'dob' => 'nullable|date|date_format:Y-m-d',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $user = $request->user();
            $fields = [];
            if($request->has('name'))
                $fields['name'] = $request->name;
            if($request->has('nickname'))
                $fields['nickname'] = $request->nickname;
            if($request->has('address'))
                $fields['address'] = $request->address;
            if($request->has('dob'))
                $fields['dob'] = $request->dob;
            if($request->has('address'))
                $fields['address'] = $request->address;
            if($request->has('latitude'))
                $fields['latitude'] = $request->latitude;
            if($request->has('longitude'))
                $fields['longitude'] = $request->longitude;
            if(!empty($fields))
                $user->update($fields);


            return response()->json([
                'status' => 200,
                'message' => 'User Updated',
            ], 200);
        }
        catch (\Throwable $th){

            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function me(Request $request)
    {
        try {
            $user = $request->user();
            $data['status'] = 200;
            $data['me'] = $user;
            $data['verified'] = $this->setting['default_verification_method'] == 'email' ? $user->email_verified_at : $user->phone_verified_at;
            return  response()->json($data, 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function verifyIdentity(Request $request)
    {

        try {
            $validator = \Validator::make($request->all(), [
                'identity_card' => 'required|file|mimes:png,jpg,jpeg',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $user = $request->user();
            $image = "";
            if ($request->hasFile('identity_card')) {
                $imageFile = $request->identity_card;
                $file_name = uploadFile($imageFile, imagePath());
                $image = $file_name;
            }

            if($image != ''){
                $user->update(['identity_card' => $image]);
                $admin = User::find(1);
                $admin->newVerificationRequestNotificaton();

                return response()->json(['user' => $user, 'status' => 200], 200);
            }


        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }

    }
}

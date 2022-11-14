<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function update(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
                'nickname' => 'nullable|min:3',
                'address' => 'nullable|min:3',
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
            return  response()->json($data, 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
}

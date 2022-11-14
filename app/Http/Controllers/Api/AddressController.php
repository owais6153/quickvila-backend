<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function setGeoLocation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if ($validator->fails()) {
            $error['errors'] = $validator->messages();
            $error['status'] = 400;
            return response()->json($error, 400);
        }

        try{
            $user = $request->user();
            Address::where('user_id', $user_id)->update([
                'is_active' => false,
            ]);
            $address = Address::create([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'user_id' => $user->id,
                'is_active' => true,
            ]);
        }
        catch (\Throwable $th){

            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
}

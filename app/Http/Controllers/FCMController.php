<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceToken;

class FCMController extends Controller
{
    public function registerToken(Request $req){
        $user_id = Auth::user()->id;
        $check = DeviceToken::where('token', $req->token)->first();
        if(empty($check)){
            $token = DeviceToken::create([
                'token' => $req->token,
                'user_id' => $user_id,
            ]);
        }
        return response()->json(['message' => 'token created', 'status' => 200], 200);
    }
    public function sendNotification(Request $req)
    {
        Auth::user()->storeNewOrder();
        dd(Auth::user()->devicetokens);
    }
}

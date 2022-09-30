<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderProduct;

class CheckoutController extends Controller
{
    public function add_new_order(Cart $cart, User $user){
        try{

            $order = new Order();
            $order->user_id = $user->id;
            $order->total = $cart->total;
            $order->count = $cart->count;
            $order->save();
            $items = $cart->items;

            return true;
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }

    }
    public function checkout(Request $request){

        try{
            $user = $request->user();
            $is_payment_successfull = true;
            if($is_payment_successfull){
                $cart  = Cart::where('user_id', $user->id)->first();
                if(!empty($cart)){
                    if($cart->items->count()){
                       $newOrder =  $this->add_new_order($cart, $user);
                       if($newOrder){

                       }

                        $error['errors'] = ['error' => ['Something went wrong in adding new order.']];
                        $error['status'] = 500;
                        return response()->json($error, 500);
                    }

                    $error['errors'] = ['error' => ['No item in cart']];
                    $error['status'] = 404;

                    return response()->json($error, 404);
                }

                $error['errors'] = ['error' => ['Cart Not Found Against This User']];
                $error['status'] = 404;

                return response()->json($error, 404);
            }


            $error['errors'] = ['error' => ['Payment Failed']];
            $error['status'] = 401;

            return response()->json($error, 401);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
}

<?php
namespace App\Services\CartServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;


class CartService
{
    public function getCart(Request $request)
    {
        if(Auth::check()){
            $user_id = $request->user()->id;
            $cart =  Cart::where('user_id', $user_id)->first();
            if(empty($cart) && $request->has('identifier')){
                $cart =  Cart::where('identifier', $request->identifier)->first();
                if(!empty($cart))
                    $cart->update(['user_id', $user_id]);
            }
            return $cart;
        }
        else{
            if($request->has('identifier')){
                return Cart::where('identifier', $request->identifier)->first();
            }
        }
        return [];
    }
    public function getDetailedCart($identifier){
        return Cart::with(['items', 'items.variation', 'items.product'])->where('identifier', $identifier)->first();
    }

}

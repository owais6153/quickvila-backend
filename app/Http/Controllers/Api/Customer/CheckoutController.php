<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Exception;

class CheckoutController extends Controller
{

    public $order;
    public $error;
    public function add_new_order(Cart $cart, Request $request){
        try{


            $user_id = $request->user()->id;

            $order = Order::create([
                'count' => $cart->count,
                'sub_total' => $cart->sub_total,
                'platform_charges' => $cart->platform_charges,
                'tax' => $cart->tax,
                'total' => $cart->total,
                'tip' => $request->tip,
                'order_no' => $cart->identifier,
                'status' => InProcess(),
                'user_id' => $user_id
            ]);
            foreach($cart->items as $item){
                $orderItem = OrderProduct::create([
                    'line_total' => $item->line_total,
                    'qty' => $item->qty,
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'store_id' => $item->product->store_id,
                    'variation_id' => $item->variation_id,
                ]);
            }

            $this->order = $order;

            return true;
        }
        catch (\Throwable $th) {
            $this->error = $th->getMessage();
            return false;
        }
    }
    public function checkout(Request $request){

        try{
            $user_id = $request->user()->id;

            $is_payment_successfull = true;
            if($is_payment_successfull){
                $cart  = Cart::where('identifier', $request->identifier)->orWhere('user_id', $user_id)->first();
                if(!empty($cart)){

                    if($cart->items->count()){
                       $newOrder =  $this->add_new_order($cart, $request);
                       if($newOrder === true){
                            $cart->items()->delete();
                            $cart->delete();

                            $data = array(
                                'order' => $this->order,
                                'status' => 200
                            );
                            return response()->json($data, 200);
                       }

                       throw new Exception($this->error, 503);

                    }

                    throw new Exception('No item in cart', 404);
                }


                throw new Exception('Cart Not Found Against This User', 404);
            }

            throw new Exception('Payment Failed', 401);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
}

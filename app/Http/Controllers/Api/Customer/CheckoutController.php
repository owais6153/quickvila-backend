<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use Exception;

class CheckoutController extends Controller
{

    public $order;
    public $error;
    public function add_new_order(Cart $cart, User $user){
        try{

            $order = Order::create([
                'count' => $cart->count,
                'tax' => '0',
                'delivery_charges' => '0',
                'total' => $cart->total,
                'latitude' => '24',
                'longitude' => '24',
                'status' => InProcess(),
                'address' => InProcess(),
                'user_id' => $cart->user_id,
            ]);
            foreach($cart->items as $item){
                $orderItem = OrderProduct::create([
                    'line_total' => $item->line_total,
                    'qty' => $item->qty,
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'store_id' => $item->product->store_id,
                    'is_refund' => false
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
            $user = $request->user();
            $is_payment_successfull = true;
            if($is_payment_successfull){
                $cart  = Cart::where('user_id', $user->id)->first();
                if(!empty($cart)){

                    if($cart->items->count()){
                       $newOrder =  $this->add_new_order($cart, $user);
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

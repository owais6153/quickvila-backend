<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartProduct;

class CartController extends Controller
{
    public function index(Request $request)
    {
        try{
            $user_id = $request->user()->id;
            $data['cart'] = Cart::with(['items', 'items.product'])->where('user_id', $user_id)->first();
            if(empty($data['cart'])){
                $data['cart'] = [];
            }
            $data['status'] = 200;
            return  response()->json($data, 200);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }


    }
    public function add(Product $product, Request $request)
    {
        try{
            $user_id = $request->user()->id;
            $cart = Cart::where('user_id', $user_id)->first();
            if(!empty($cart)){
                $cartItem = CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
                if(!empty($cartItem)){
                    $qty = $cartItem->qty + 1;
                    $cartItem->update([
                        'qty' => $qty,
                        'line_total' => ($product->sale_price) ? $product->sale_price * $qty : $product->price * $qty,
                    ]);
                }
                else{
                    $cartItem = CartProduct::create([
                        'qty' => 1,
                        'line_total' => ($product->sale_price) ? $product->sale_price : $product->price,
                        'product_id' => $product->id,
                        'cart_id' => $cart->id
                    ]);
                }
                $cart->update([
                    'count' => $cart->count + 1,
                    'total' => ($product->sale_price) ? $cart->total + $product->sale_price : $cart->total + $product->price
                ]);
            }
            else{
                $cart = Cart::create([
                    'count' => 1,
                    'total' => ($product->sale_price) ? $product->sale_price : $product->price,
                    'user_id' => $user_id
                ]);

                $cartItem = CartProduct::create([
                    'qty' => 1,
                    'line_total' => ($product->sale_price) ? $product->sale_price : $product->price,
                    'product_id' => $product->id,
                    'cart_id' => $cart->id
                ]);
            }

            $data['cart'] = Cart::with(['items', 'items.product'])->where('user_id', $user_id)->first();
            $data['status'] = 200;
            $data['message'] = 'Product added to cart';
            return  response()->json($data, 200);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function emptyCart(Request $request){
        try{
            $user_id = $request->user()->id;
            $cart = Cart::where('user_id', $user_id)->first();
            if($cart->items->count())
                $cart->items()->delete();
            if(!empty($cart))
                $cart->delete();

            $data['status'] = 200;
            $data['message'] = 'Cart is empty';
            return  response()->json($data, 200);

        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function remove(CartProduct $cartProduct, Request $request)
    {
        try{
            $user_id = $request->user()->id;
            $item_id = $cartProduct->cart_id;
            $cart = Cart::where('id', $item_id)->where('user_id', $user_id)->first();
            if(!empty($cart)){
                $cart->update([
                    'count' => $cart->count - $cartProduct->qty,
                    'total' => $cart->total - $cartProduct->line_total,
                ]);
                $cartProduct->delete();
                $data['cart'] = Cart::with(['items', 'items.product'])->where('id', $item_id)->first();
                if(empty($data['cart'])){
                    $data['cart'] = [];
                }
                $data['status'] = 200;
                $data['message'] = 'Item Removed';
                return  response()->json($data, 200);
            }

            $error['status'] = 400;

            $error['errors'] = ['error' => ['This item not belongs to your cart']];
            return  response()->json($error, 400);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function update(CartProduct $cartProduct, $operation, Request $request)
    {
        try{
            $user_id = $request->user()->id;
            $cart = Cart::where('user_id', $user_id)->first();
            $product = $cartProduct->product;
            if($operation == 'increment'){
                $qty = $cartProduct->qty + 1;
                $cartProduct->update([
                    'qty' => $qty,
                    'line_total' => ($product->sale_price) ? $product->sale_price * $qty : $product->price * $qty,
                ]);
                $cart->update([
                    'count' => $cart->count + 1,
                    'total' => ($product->sale_price) ? $cart->total + $product->sale_price : $cart->total + $product->price
                ]);

                $data['cart'] = Cart::with(['items', 'items.product'])->where('user_id', $user_id)->first();
                $data['status'] = 200;
                $data['message'] = 'Quantity Updated';
                return  response()->json($data, 200);
            }
            else if($operation == 'decrement'){
                $qty = $cartProduct->qty - 1;
                if($qty == 0){
                    $error['errors'] = ['Quantity' => ['Minimum Quantity Should Be 1']];
                    $error['status'] = 500;
                    return response()->json($error, 500);
                }
                $cartProduct->update([
                    'qty' => $qty,
                    'line_total' => ($product->sale_price) ? $product->sale_price * $qty : $product->price * $qty,
                ]);
                $cart->update([
                    'count' => $cart->count - 1,
                    'total' => ($product->sale_price) ? $cart->total - $product->sale_price : $cart->total - $product->price
                ]);

                $data['cart'] = Cart::with(['items', 'items.product'])->where('user_id', $user_id)->first();
                $data['status'] = 200;
                $data['message'] = 'Quantity Updated';
                return  response()->json($data, 200);
            }
            $error['$operation'] = $operation;
            $error['errors'] = ['Operation' => ['Operation not defined']];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
}

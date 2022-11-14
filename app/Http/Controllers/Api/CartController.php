<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartProduct;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function getCart(Request $request)
    {
        if(Auth::check()){
            $user_id = $request->user()->id;
            return Cart::where('user_id', $user_id)->first();
        }
        else{
            if($request->has('identifier')){
                return Cart::where('identifier', $request->identifier)->first();
            }
        }
        return [];
    }
    public function index(Request $request)
    {
        try{
            $data['cart'] = $this->getCart($request);
            if(empty($data['cart'])){
                $data['cart'] = [];
            }
            else{
                $data['cart'] = Cart::with(['items', 'items.product'])->where('identifier', $data['cart']->identifier)->first();
            }
            $data['status'] = 200;
            $identifier = isset($cart->identifier) ? $cart->identifier : false;
            return  response()->json($data, 200)->cookie('cart', $identifier);
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
            $cart = $this->getCart($request);
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
                $identifier = time();
                $cart = Cart::create([
                    'count' => 1,
                    'total' => ($product->sale_price) ? $product->sale_price : $product->price,
                    'identifier' => $identifier,
                    'ip' => $request->ip()
                ]);

                $cartItem = CartProduct::create([
                    'qty' => 1,
                    'line_total' => ($product->sale_price) ? $product->sale_price : $product->price,
                    'product_id' => $product->id,
                    'cart_id' => $cart->id
                ]);
                if(Auth::check()){
                    $cart->update([
                        'user_id' => $request->user()->id,
                    ]);
                }
            }

            $identifier = isset($cart->identifier) ? $cart->identifier : false;
            $data['cart'] = Cart::with(['items', 'items.product'])->where('identifier', $identifier)->first();
            $data['status'] = 200;
            $data['message'] = 'Product added to cart';
            return  response()->json($data, 200)->cookie('cart', $identifier);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
    public function emptyCart(Request $request){
        try{
            $cart = $this->getCart($request);

            if(!empty($cart)){
                if($cart->items->count()){
                    $cart->items()->delete();
                }
                $cart->delete();
                $data['status'] = 200;
                $data['message'] = 'Cart is empty';
                return  response()->json($data, 200);
            }

            $error['errors'] = ['error' => ['No Item in cart']];
            $error['status'] = 500;
            return response()->json($error, 500);
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
            $item_id = $cartProduct->cart_id;
            $cart = $this->getCart($request);
            if(!empty($cart)){

                $cart->update([
                    'count' => $cart->count - $cartProduct->qty,
                    'total' => $cart->total - $cartProduct->line_total,
                ]);
                $cartProduct->delete();

                $identifier = isset($cart->identifier) ? $cart->identifier : false;
                $data['cart'] = Cart::with(['items', 'items.product'])->where('identifier', $identifier)->first();
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
            $cart = $this->getCart($request);
            $identifier = isset($cart->identifier) ? $cart->identifier : false;
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

                $data['cart'] = Cart::with(['items', 'items.product'])->where('identifier', $identifier)->first();
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

                $data['cart'] = Cart::with(['items', 'items.product'])->where('identifier', $identifier)->first();
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

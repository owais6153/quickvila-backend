<?php

namespace App\Http\Controllers\Api\Customer;

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
    public function index(Request $request)
    {
        try{
            $data['cart'] = $this->getCart($request);
            if(empty($data['cart'])){
                $data['cart'] = [];
            }
            else{
                $data['cart'] = Cart::with(['items', 'items.variation', 'items.product'])->where('identifier', $data['cart']->identifier)->first();
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
            $variation = null;
            if($request->has('variation')){
                $variation = $request->variation;
            }
            $cart = $this->getCart($request);
            if(!empty($cart)){
                $cartItem = CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->where('variations_id', $variation)->first();
                if(!empty($cartItem)){
                    $qty = $cartItem->qty + 1;
                    $line_total = 0;
                    if($variation == null){
                        $line_total = ($product->sale_price) ? $product->sale_price * $qty : $product->price * $qty;
                    }
                    else{
                        $variant = $product->variations()->where('id', $variation)->first();
                        $line_total = ($variant->sale_price) ? $variant->sale_price * $qty : $variant->price * $qty;
                    }

                    $cartItem->update([
                        'qty' => $qty,
                        'line_total' => $line_total,
                    ]);
                }
                else{

                    $line_total = 0;
                    if($variation == null){
                        $line_total = ($product->sale_price) ? $product->sale_price : $product->price;
                    }
                    else{
                        $variant = $product->variations()->where('id', $variation)->first();
                        $line_total = ($variant->sale_price) ? $variant->sale_price : $variant->price;
                    }

                    $cartItem = CartProduct::create([
                        'qty' => 1,
                        'line_total' => $line_total,
                        'product_id' => $product->id,
                        'cart_id' => $cart->id,
                        'variations_id' => $variation,
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

                $line_total = 0;
                if($variation == null){
                    $line_total = ($product->sale_price) ? $product->sale_price : $product->price;
                }
                else{
                    $variant = $product->variations()->where('id', $variation)->first();
                    $line_total = ($variant->sale_price) ? $variant->sale_price : $variant->price;
                }


                $cartItem = CartProduct::create([
                    'qty' => 1,
                    'line_total' => $line_total,
                    'product_id' => $product->id,
                    'cart_id' => $cart->id,
                    'variations_id' => $variation,
                ]);
                if(Auth::check()){
                    $cart->update([
                        'user_id' => $request->user()->id,
                    ]);
                }
            }

            $identifier = isset($cart->identifier) ? $cart->identifier : false;
            $data['cart'] = Cart::with(['items', 'items.product', 'items.variation'])->where('identifier', $identifier)->first();
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
                $data['cart'] = Cart::with(['items', 'items.product', 'items.variation'])->where('identifier', $identifier)->first();
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

                $line_total = 0;
                if($cartProduct->product->product_type != "variation"){
                    $line_total = ($product->sale_price) ? $product->sale_price * $qty : $product->price * $qty;
                }
                else{
                    $variant = $product->variations()->where('id', $cartProduct->variations_id)->first();
                    $line_total = ($variant->sale_price) ? $variant->sale_price * $qty : $variant->price * $qty;
                }



                $cartProduct->update([
                    'qty' => $qty,
                    'line_total' => $line_total,
                ]);
                $cart->update([
                    'count' => $cart->count + 1,
                    'total' => ($product->sale_price) ? $cart->total + $product->sale_price : $cart->total + $product->price
                ]);

                $data['cart'] = Cart::with(['items', 'items.product', 'items.variation'])->where('identifier', $identifier)->first();
                $data['status'] = 200;
                $data['message'] = 'Quantity Updated';
                return  response()->json($data, 200);
            }
            else if($operation == 'decrement'){
                $qty = $cartProduct->qty - 1;

                $line_total = 0;
                if($cartProduct->product->product_type != "variation"){
                    $line_total = ($product->sale_price) ? $product->sale_price * $qty : $product->price * $qty;
                }
                else{
                    $variant = $product->variations()->where('id', $cartProduct->variations_id)->first();
                    $line_total = ($variant->sale_price) ? $variant->sale_price * $qty : $variant->price * $qty;
                }

                if($qty == 0){
                    $error['errors'] = ['Quantity' => ['Minimum Quantity Should Be 1']];
                    $error['status'] = 500;
                    return response()->json($error, 500);
                }
                $cartProduct->update([
                    'qty' => $qty,
                    'line_total' => $line_total,
                ]);
                $cart->update([
                    'count' => $cart->count - 1,
                    'total' => ($product->sale_price) ? $cart->total - $product->sale_price : $cart->total - $product->price
                ]);

                $data['cart'] = Cart::with(['items', 'items.product', 'items.variation'])->where('identifier', $identifier)->first();
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

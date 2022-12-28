<?php
namespace App\Services\CartServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartProduct;

class CartService
{

    protected $setting = false;
    function __construct()
    {
        $this->setting = getSetting('tax');
    }
    public function getCartWithIdentifier($identifier)
    {
        return Cart::with(['items', 'items.variation', 'items.product'])->where('identifier', $identifier)->first();
    }

    public function getCart(Request $request)
    {
        if(Auth::check()){
            $user_id = $request->user()->id;
            $cart =  Cart::with(['items', 'items.variation', 'items.product'])->where('user_id', $user_id)->first();
            if(empty($cart) && $request->has('identifier')){
                $cart =  Cart::with(['items', 'items.variation', 'items.product'])->where('identifier', $request->identifier)->first();
                if(!empty($cart))
                    $cart->update(['user_id', $user_id]);
            }
            return $cart;
        }
        else{
            if($request->has('identifier')){
                return Cart::with(['items', 'items.variation', 'items.product'])->where('identifier', $request->identifier)->first();
            }
        }
        return [];
    }

    public function ifAuth(Cart $cart)
    {
        if(Auth::check() && $cart->user_id == null){
            $cart->update([
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function getPlatformCahrges($subtotal)
    {
        $pf = ($subtotal / 100) * $this->setting['platform_fees'];
        if($pf > 4){
            $pf = 4;
        }
        if($pf < 2){
            $pf = 2;
        }
        return $pf;
    }

    public function getDeliveryCharges($cart)
    {
        return 0;
    }
    public function getTax($subtotal, $pf, $dc)
    {
        $t = $subtotal + $pf + $dc;
        $t = ($t / 100) * $this->setting['tax'];
        return $t;
    }

    public function updateTaxAndCharges(Cart $cart)
    {
        $subtotal = $cart->sub_total;
        $total = $cart->total;
        $platform_fee = $this->getPlatformCahrges($subtotal);
        $delivery_fee = $this->getDeliveryCharges($cart);
        $tax = $this->getTax($subtotal,$platform_fee, $delivery_fee);

        $cart->update([
            'platform_charges' => $platform_fee,
            'tax' => $tax,
            'delivery_charges' => $delivery_fee,
            'total' => $delivery_fee + $platform_fee + $tax + $subtotal,
        ]);
    }

    public function addToCart(Product $product, Request $request)
    {
        $cart = $this->getCart($request);
        $variation = null;
        if($request->has('variation')){
            $variation = $request->variation;
        }

        if(!empty($cart)){
            $cartItem = CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->where('variations_id', $variation)->first();
            if(!empty($cartItem)){
                $qty = $cartItem->qty + 1;
                $line_total = 0;
                $item_price = 0;
                if($variation == null){
                    $line_total = ($product->sale_price_to_display) ? $product->sale_price_to_display * $qty : $product->price_to_display * $qty;
                    $item_price = ($product->sale_price_to_display) ? $product->sale_price_to_display : $product->price_to_display;
                }
                else{
                    $variant = $product->variations()->where('id', $variation)->first();
                    $line_total = ($variant->sale_price_to_display) ? $variant->sale_price_to_display * $qty : $variant->price_to_display * $qty;
                    $item_price = ($variant->sale_price_to_display) ? $variant->sale_price_to_display : $variant->price_to_display;
                }
                $cartItem->update([
                    'qty' => $qty,
                    'line_total' => $line_total,
                ]);
                $cart->update([
                    'count' => $cart->count + 1,
                    'total' =>   $cart->total + $item_price,
                    'sub_total' =>   $cart->sub_total + $item_price,
                ]);
                $this->updateTaxAndCharges($cart);
            }
            else{

                $line_total = 0;
                if($variation == null){
                    $line_total = ($product->sale_price_to_display) ? $product->sale_price_to_display : $product->price_to_display;
                }
                else{
                    $variant = $product->variations()->where('id', $variation)->first();
                    $line_total = ($variant->sale_price_to_display) ? $variant->sale_price_to_display : $variant->price_to_display;
                }

                $cartItem = CartProduct::create([
                    'qty' => 1,
                    'line_total' => $line_total,
                    'product_id' => $product->id,
                    'cart_id' => $cart->id,
                    'variations_id' => $variation,
                ]);

                $cart->update([
                    'count' => $cart->count + 1,
                    'total' =>   $cart->total + $line_total,
                    'sub_total' =>   $cart->sub_total + $line_total,
                ]);

                $this->updateTaxAndCharges($cart);
            }

        }
        else{
            $identifier = time();

            $line_total = 0;
            if($variation == null){
                $line_total = ($product->sale_price_to_display) ? $product->sale_price_to_display : $product->price_to_display;
            }
            else{
                $variant = $product->variations()->where('id', $variation)->first();
                $line_total = ($variant->sale_price_to_display) ? $variant->sale_price_to_display : $variant->price_to_display;
            }

            $cart = Cart::create([
                'sub_total' =>  $line_total,
                'tax' => 0,
                'delivery_charges' => 0,
                'count' => 1,
                'total' => $line_total,
                'identifier' => $identifier,
                'ip' => $request->ip(),
            ]);


            $cartItem = CartProduct::create([
                'qty' => 1,
                'line_total' => $line_total,
                'sub_total' =>   $cart->sub_total + $line_total,
                'product_id' => $product->id,
                'cart_id' => $cart->id,
                'variations_id' => $variation,
            ]);

            $this->updateTaxAndCharges($cart);
        }

        $this->ifAuth($cart);
        return $this->getCartWithIdentifier($cart->identifier);
    }

    public function emptyCart( Request $request)
    {
        $cart = $this->getCart($request);
        if(!empty($cart)){
            if($cart->items->count()){
                $cart->items()->delete();
            }
            $cart->delete();
        }
    }
    public function remove(CartProduct $cartProduct, Request $request)
    {
        $cart = $this->getCart($request);
        $this->ifAuth($cart);
        if(!empty($cart)){
            $cart->update([
                'count' => $cart->count - $cartProduct->qty,
                'total' => $cart->total - $cartProduct->line_total,
                'sub_total' => $cart->sub_total - $cartProduct->line_total,
            ]);
            $cartProduct->delete();

            $this->updateTaxAndCharges($cart);
        }

    }
    public function updateCart(CartProduct $cartProduct, $operation, Request $request)
    {
        $cart = $this->getCart($request);
        $this->ifAuth($cart);
        $identifier = isset($cart->identifier) ? $cart->identifier : false;
        $product = $cartProduct->product;


        if($operation == 'increment'){
            $qty = $cartProduct->qty + 1;

            $line_total = 0;
            $item_price = 0;
            if($cartProduct->product->product_type != "variation"){
                $line_total = ($product->sale_price_to_display) ? $product->sale_price_to_display * $qty : $product->price_to_display * $qty;
                $item_price = ($product->sale_price_to_display) ? $product->sale_price_to_display : $product->price_to_display;
            }
            else{
                $variant = $product->variations()->where('id', $cartProduct->variations_id)->first();
                $line_total = ($variant->sale_price_to_display) ? $variant->sale_price_to_display * $qty : $variant->price_to_display * $qty;
                $item_price = ($variant->sale_price_to_display) ? $variant->sale_price_to_display : $variant->price_to_display;
            }

            $cartProduct->update([
                'qty' => $qty,
                'line_total' => $line_total,
            ]);
            $cart->update([
                'count' => $cart->count + 1,
                'total' =>  $cart->total + $item_price,
                'sub_total' =>  $cart->sub_total + $item_price
            ]);

            $this->updateTaxAndCharges($cart);

        }
        else if($operation == 'decrement'){
            $qty = $cartProduct->qty - 1;

            $line_total = 0;
            $item_price = 0;
            if($cartProduct->product->product_type != "variation"){
                $line_total = ($product->sale_price_to_display) ? $product->sale_price_to_display * $qty : $product->price_to_display * $qty;
                $item_price = ($product->sale_price_to_display) ? $product->sale_price_to_display : $product->price_to_display;
            }
            else{
                $variant = $product->variations()->where('id', $cartProduct->variations_id)->first();
                $line_total = ($variant->sale_price_to_display) ? $variant->sale_price_to_display * $qty : $variant->price_to_display * $qty;
                $item_price = ($variant->sale_price_to_display) ? $variant->sale_price_to_display : $variant->price_to_display;
            }

            if($qty != 0){
                $cartProduct->update([
                    'qty' => $qty,
                    'line_total' => $line_total,
                ]);


                $cart->update([
                    'count' => $cart->count - 1,
                    'total' =>  $cart->total - $item_price,
                    'sub_total' =>  $cart->sub_total - $item_price
                ]);

            $this->updateTaxAndCharges($cart);
            }

        }
    }

}

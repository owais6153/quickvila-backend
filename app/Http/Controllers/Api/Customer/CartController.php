<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartProduct;
use Illuminate\Support\Facades\Auth;
use App\Services\CartServices\CartService;


class CartController extends Controller
{

    function __construct(){
        $this->service = new CartService();
    }


    public function index(Request $request)
    {
        try{
            $data['cart'] = $this->service->getCart($request);
            $data['status'] = 200;
            $identifier = isset($cart->identifier) ? $cart->identifier : false;
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

            if($product->product_type == 'variation' && !$request->has('variation')){

                $error['errors'] = ['variation' => ['Please Select Variation Options.']];
                $error['status'] = 400;

                return response()->json($error, 400);
            }
            $cart = $this->service->addToCart($product, $request);
            $data['cart'] = $cart;
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

            $this->service->emptyCart($request);

            $data['status'] = 200;
            $data['message'] = 'Cart is now empty';
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
            $cart = $this->service->remove($cartProduct, $request);
            $data['cart'] = $this->service->getCart($request);
            $data['status'] = 200;
            $data['message'] = 'Item Removed';
            return  response()->json($data, 200);
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
            $this->service->updateCart($cartProduct, $operation, $request);

            $data['cart'] = $this->service->getCart($request);
            $data['status'] = 200;
            $data['message'] = 'Quantity Updated';
            return  response()->json($data, 200);
        }
        catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
}

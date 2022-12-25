<?php
namespace App\Services\ProductServices;
use App\Models\Product;
use App\Models\Store;

class PricingService
{
    public function updateDisplayPrice($id){

        $product = Product::find($id);

        $store = Store::with('setting')->where('id', $product->store_id)->first();
        $additionalPrices = $store->setting->price;
        if($store->setting->price_condition == 'price'){
            $product->price_to_display = $product->price + $additionalPrices;
            if($product->sale_price != null){
                $product->sale_price_to_display = $product->sale_price + $additionalPrices;
            }
        }
        else{
            $product->price_to_display = $product->price + (($product->price / 100) * $additionalPrices);
            if($product->sale_price != null){
                $product->sale_price_to_display = $product->sale_price + (($product->sale_price / 100) * $additionalPrices);
            }
        }

        $product->saveQuietly();
        return $product;
    }
}

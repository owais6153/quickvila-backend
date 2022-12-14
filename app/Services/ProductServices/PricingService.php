<?php
namespace App\Services\ProductServices;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Store;

class PricingService
{
    public function updateDisplayPrice($id){

        $product = Product::find($id);
        $store = Store::with('setting')->where('id', $product->store_id)->first();
        $additionalPrices = $store->setting->price;
        if($product->is_taxable){
            if($store->setting->price_condition == 'price'){
                $product->price_to_display = $product->price + $additionalPrices;
                if($product->sale_price != null){
                    $product->sale_price_to_display = $product->sale_price + $additionalPrices;
                }
                else{
                    $product->sale_price_to_display = null;
                }
            }
            else{
                $product->price_to_display = $product->price + (($product->price / 100) * $additionalPrices);
                if($product->sale_price != null){
                    $product->sale_price_to_display = $product->sale_price + (($product->sale_price / 100) * $additionalPrices);
                }
                else{
                    $product->sale_price_to_display = null;
                }
            }
        }
        else{
            $product->price_to_display = $product->price;
            $product->sale_price_to_display = $product->sale_price;
        }
        $product->saveQuietly();
        return $product;
    }
    public function updateVariationDisplayPrice($id){

        $variation = Variation::find($id);

        $s_id = $variation->product->store_id;
        $store = Store::with('setting')->find($s_id);
        $product = $variation->product;

        $additionalPrices = $store->setting->price;
        if($product->is_taxable){
            if($store->setting->price_condition == 'price'){
                $variation->price_to_display = $variation->price + $additionalPrices;
                if($variation->sale_price != null){
                    $variation->sale_price_to_display = $variation->sale_price + $additionalPrices;
                }
                else{
                    $variation->sale_price_to_display = null;
                }
            }
            else{
                $variation->price_to_display = $variation->price + (($variation->price / 100) * $additionalPrices);
                if($variation->sale_price != null){
                    $variation->sale_price_to_display = $variation->sale_price + (($variation->sale_price / 100) * $additionalPrices);
                }
                else{
                    $variation->sale_price_to_display = null;
                }
            }
        }
        else{
            $variation->price_to_display = $variation->price;
            $variation->sale_price_to_display = $variation->sale_price;
        }
        $variation->saveQuietly();
        return $variation;
    }

}

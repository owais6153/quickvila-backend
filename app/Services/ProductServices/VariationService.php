<?php
namespace App\Services\ProductServices;
use App\Models\Variation;
use App\Models\Product;
use App\Models\Attribute;

class VariationService
{
    public function create($variations, Product $product)
    {
        foreach($variations as $variation){
            Variation::create([
                'name' => $variation['name'],
                'price' => $variation['price'],
                'sale_price' => (isset($variation['sale_price'])) ?  $variation['sale_price'] : null,
                'variants' => $variation['variants'],
                'product_id' => $product->id,
            ]);
        }
    }
    public function generateVariations($array)
    {
        if (empty($array)) {
            return [];
        }

        function traverse($array, $parent_ind)
        {
            $r = [];
            $pr = '';

            if (!is_numeric($parent_ind)) {
                $pr = $parent_ind . '-';
            }

            foreach ($array as $ind => $el) {
                if (is_array($el)) {
                    $r = array_merge($r, traverse($el, $pr . (is_numeric($ind) ? '' : $ind)));
                } elseif (is_numeric($ind)) {
                    $r[] = $pr . $el;
                } else {
                    $r[] = $pr . $ind . '-' . $el;
                }
            }

            return $r;
        }

        //1. Go through entire array and transform elements that are arrays into elements, collect keys
        $keys = [];
        $size = 1;

        foreach ($array as $key => $elems) {
            if (is_array($elems)) {
                $rr = [];

                foreach ($elems as $ind => $elem) {
                    if (is_array($elem) && !isset($elem['name']) && !isset($elem['media'])) {
                        $rr = array_merge($rr, traverse($elem, $ind));
                    } else {
                        $rr[] = $elem;
                    }
                }

                $array[$key] = $rr;
                $size *= count($rr);
            }

            $keys[] = $key;
        }



        //2. Go through all new elems and make variations
        $rez = [];
        for ($i = 0; $i < $size; $i++) {
            $rez[$i] = [];

            foreach ($array as $key => $value) {
                $current = current($array[$key]);
                $rez[$i][$key] = $current;
            }

            foreach ($keys as $key) {
                if (!next($array[$key])) {
                    reset($array[$key]);
                } else {
                    break;
                }
            }
        }

        return $rez;
    }
    public function getAllPossibleVariants($variation_attr)
    {
        $attributes = Attribute::whereIn('id', $variation_attr)->with(['options' => function ($q) {
            $q->select('name', 'id', 'media', 'attr_id');
        }])->whereHas('options')->get()->toArray();

        $options = [];
        foreach ($attributes as $attribute) {
            foreach ($attribute['options'] as $option) {
                $options[$attribute['name']][] = $option;
            }
        }

        $variants = $this->generateVariations($options);

        return $variants;
    }
}

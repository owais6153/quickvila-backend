<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;


class ProductCategoryController extends Controller
{
    public function index()
    {
        $data['categories'] = ProductCategory::all();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function products(Request $request)
    {
        $limit = ($request->has('limit')) ? $request->limit : 10;
        $products = Product::whereHas('categories', function ($q) use($request){
            $q->whereIn('product_categories.id', $request->categories);
        })->where('status', Published())->orderBy('id', 'desc')->paginate($limit);


        $data['products'] = $products;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

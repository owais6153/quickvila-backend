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
        $stores = Product::whereHas('categories', function ($q) use($request){
            $q->whereIn('product_categories.id', $request->categories);
        })->where('status', 'published')->limit($limit)->orderBy('id', 'desc');
        if($stores->count() < 1){
            $error['errors'] = ['error' => ['404 Not found']];
            $error['status'] = 404;

            return response()->json($error, 404);
        }

        $data['products'] = $stores->get();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

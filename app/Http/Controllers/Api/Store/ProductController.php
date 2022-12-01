<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $mystore = $request->mystore;
        $data = [
            'products' => Product::where('store_id', $mystore->id)->get(),
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}

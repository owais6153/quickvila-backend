<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    function __construct(Product $product){
        $this->model = $product;
    }
    public function index(Request $request)
    {

        $products = $this->model->query();
        $paginate = ($request->has('paginate')) ? $request->paginate : 20;
        $products = $products->paginate($paginate);
        $data['products'] = $products;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
        return response()->json($data, $data['status']);
    }
}

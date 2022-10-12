<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;

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
    }
    public function show(Store $store, Product $product, Request $request){
        $data['product'] = $product;
        $data['status'] = 200;
        $limit = ($request->has('limit')) ? $request->limit : 10;
        $data['related'] = $this->model->limit($limit)->get();
        return response()->json($data, $data['status']);
    }
    public function storeProducts(Store $store, Request $request){
        $products = $this->model->where('store_id', $store->id);
        $paginate = ($request->has('paginate')) ? $request->paginate : 20;
        $products = $products->paginate($paginate);
        $data['products'] = $products;
        $data['store'] = $store;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
        return response()->json($data, $data['status']);
    }
}

<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Services\ProductServices\VariationService;

class ProductController extends Controller
{
    function __construct(Product $product){
        $this->model = $product;
        $this->variationservice = new VariationService();
    }
    public function index(Request $request)
    {
        $products = $this->model->query();
        $paginate = ($request->has('paginate')) ? $request->paginate : 20;
        $products = $products->where('status', Published())->orderBy('id', 'desc')->paginate($paginate);
        $data['products'] = $products;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function show(Store $store, Product $product, Request $request){
        $data['product'] = Product::with(['variations', 'store'])->withCount(['reviews'])->where('id', $product->id)->first();
        $data['reviews'] = $data['product']->reviews()->with(['author'])->paginate(10);
        $data['average_rating'] = number_format($data['product']->reviews()->avg('rating'), 1);
        $data['status'] = 200;
        $limit = ($request->has('limit')) ? $request->limit : 10;
        $data['related'] = $this->model->limit($limit)->where('store_id', $product->store_id)->where('id', '!=', $data['product']->id)->orderBy('id', 'desc')->get();


        $data['product_options'] = $this->variationservice->getAllOptions($data['product']->variations);

        return response()->json($data, $data['status']);
    }
    public function storeProducts(Store $store, Request $request){
        $products = $this->model->where('store_id', $store->id);
        $paginate = ($request->has('paginate')) ? $request->paginate : 20;
        $products = $products->where('status', Published())->orderBy('id', 'desc')->paginate($paginate);
        $data['products'] = $products;
        $data['store'] = $store;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

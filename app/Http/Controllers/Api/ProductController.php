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
        $data['products'] = $this->model->query();
        if($request->has('offset')){
            $data['products'] = $data['products']->offset($request->offset);
        }
        if($request->has('limit')){
            $data['products'] = $data['products']->limit($request->limit);
        }
        if($request->has('search')){
            $data['products'] = $data['products']->where('name', 'LIKE', "%$request->search%");
        }

        $data['products'] = $data['products']->get();
        if(!$data['products']->count()){
            $data['status'] = 404;
            $data['message'] = 'No Product Found';
        }
        else{
            $data['status'] = 200;
        }

        return response()->json($data, $data['status']);
    }
}

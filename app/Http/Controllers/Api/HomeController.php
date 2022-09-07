<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;


class HomeController extends Controller
{
    public function index()
    {
        $data['products'] = Product::limit(10)->get();
        $data['stores'] = Store::withCount(['products'])->limit(10)->get();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function search($term, Request $request)
    {
        $data['products'] = Product::query();
        if($term){
            $data['products'] = $data['products']->where('name', 'LIKE', "%$term%");
        }
        $data['products'] = $data['products']->paginate(20)->get();
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


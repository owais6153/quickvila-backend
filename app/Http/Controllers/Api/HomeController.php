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
    public function search(Type $var = null)
    {
        $data['products'] = Product::all();
        $data['stores'] = Product::all();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}


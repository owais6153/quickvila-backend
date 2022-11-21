<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreCategory;
use App\Models\Store;

class StoreCategoryController extends Controller
{
    public function index()
    {
        $data['categories'] = StoreCategory::all();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function stores(Request $request)
    {
        $limit = ($request->has('limit')) ? $request->limit : 10;
        $stores = Store::whereHas('categories', function ($q) use($request){
            $q->whereIn('store_categories.id', $request->categories);
        })->where('status', Published())->orderBy('id', 'desc')->paginate($limit);


        $data['stores'] = $stores;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

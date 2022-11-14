<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreCategory;


class StoreController extends Controller
{
    function __construct(Store $store)
    {
        $this->model = $store;
    }
    public function index(Request $request)
    {
        $stores = $this->model->query();
        $paginate = ($request->has('paginate')) ? $request->paginate : 15;
        $stores = $stores->withCount(['products'])->orderBy('id', 'desc')->paginate($paginate);
        $data['stores'] = $stores;
        $data['categories'] = StoreCategory::all();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function show($id)
    {
        $store = $this->model->where('id', $id);
        $store = $store->withCount(['products'])->with(['categories'])->first();
        $data['store'] = $store;
        $data['top_selling_products'] = $store->products()->limit(10)->orderBy('id', 'desc')->get();
        $data['featured_products'] = $store->products()->limit(10)->orderBy('id', 'desc')->get();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

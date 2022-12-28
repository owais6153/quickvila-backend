<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Services\StoreServices\StoreGeoLocationService;


class StoreController extends Controller
{
    function __construct(Store $store)
    {
        $this->model = $store;
        $this->storeLocationService = new StoreGeoLocationService();
    }
    public function index(Request $request)
    {
        $stores = $this->model->query()->where('status', Published());

        if($request->has('lat') && $request->has('long')){
            $ids = $this->storeLocationService->getNearbyStoresID($request->lat, $request->long);
            $stores=$stores->whereIn('id', $ids)->orderBy('id', 'desc');
        }

        $paginate = ($request->has('paginate')) ? $request->paginate : 15;
        $stores = $stores->withCount(['products' => function($q){
            $q->where('status', Published());
        }])->paginate($paginate);
        $data['stores'] = $stores;
        $data['categories'] = StoreCategory::all();
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function show($id, Request $request)
    {
        $store = $this->model->where('id', $id)->where('status', Published());
        $store = $store->withCount(['products' => function ($q){
            $q->where('status', Published());
        }])->with(['categories'])->first();


        $data['store'] = $store;
        $data['ratings'] = $store->reviews()->avg('rating');

        $data['product_categories'] = $store->product_categories()
        ->whereHas('products', function($q){
            $q->where('status', Published());
        })->limit(10)->orderBy('id', 'desc')->get();

        $data['top_selling_products'] = $store->products()->where('status', Published())->limit(10)->orderBy('id', 'desc')->get();
        $data['featured_products'] = $store->products()->where('status', Published())->where('is_store_featured', 1)->limit(10)->orderBy('id', 'desc')->get();

        if($request->has('lat') && $request->has('long')){
            $ids = $this->storeLocationService->getNearbyStoresID($request->lat, $request->long);
            $data['nearby_stores'] = Store::whereIn('id', $ids)->where('id', '!=', $id)->limit('10')->orderBy('id', 'desc')->get();
        }

        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

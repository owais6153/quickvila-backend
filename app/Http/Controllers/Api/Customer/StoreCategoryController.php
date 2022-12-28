<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreCategory;
use App\Models\Store;
use App\Services\StoreServices\StoreGeoLocationService;

class StoreCategoryController extends Controller
{
    function __construct()
    {
        $this->storeLocationService = new StoreGeoLocationService();
    }
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
        })->where('status', Published());


        if($request->has('lat') && $request->has('long')){
            $ids = $this->storeLocationService->getNearbyStoresID($request->lat, $request->long);
            $stores=$stores->whereIn('id', $ids)->orderBy('id', 'desc');
        }

        $stores = $stores->orderBy('id', 'desc')->paginate($limit);


        $data['stores'] = $stores;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

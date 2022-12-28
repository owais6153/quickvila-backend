<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Video;
use App\Models\StoreBanner;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Services\StoreServices\StoreGeoLocationService;

class HomeController extends Controller
{

    function __construct()
    {
        $this->storeLocationService = new StoreGeoLocationService();
    }
    public function index(Request $request)
    {
        $limit = ($request->has('limit')) ? $request->limit : 10;
        $data['store_categories'] = StoreCategory::limit($limit)->orderBy('id', 'desc')->get();
        $data['stores_purchased_from'] = Store::withCount(['products' => function ($q){
            $q->where('status', Published());
        }])->where('status', Published())->limit($limit)->orderBy('id', 'desc')->get();
        $data['featured_stores'] = Store::withCount(['products' => function ($q){
            $q->where('status', Published());
        }])->where('status', Published())->where('is_featured', 1)->limit($limit)->orderBy('id', 'desc')->get();
        $data['featured_products'] = Product::limit($limit)->where('is_site_featured', 1)->where('status', Published())->orderBy('id', 'desc')->get();
        $data['testimonials'] = Testimonial::limit($limit)->orderBy('sort', 'desc')->get();
        $data['videos'] = Video::limit($limit)->orderBy('sort', 'desc')->get();
        $data['banners'] = StoreBanner::limit(2)->orderBy('id', 'desc')->get();


        if($request->has('lat') && $request->has('long')){
            $data['nearby_stores'] = $this->storeLocationService->getNearbyStores($request->lat, $request->long);
        }

        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function search($term, Request $request)
    {
        $data['products'] = Product::query();
        if($term){
            $data['products'] = $data['products']->where('name', 'LIKE', "%$term%")->orWhere(function($q) use($term){
                $q->whereHas('store', function($q) use($term){
                    $q->where('name', 'LIKE', "%$term%")->where('status', Published());
                });
            });
        }

        $paginate = ($request->has('paginate')) ? $request->paginate : 20;

        $data['products'] = $data['products']->where('status', Published())->orderBy('id', 'desc')->paginate($paginate);
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


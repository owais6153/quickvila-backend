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


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $limit = ($request->has('limit')) ? $request->limit : 10;
        $data['top_selling_products'] = Product::limit($limit)->orderBy('id', 'desc')->get();
        $data['featured_products'] = Product::limit($limit)->where('is_site_featured', 1)->where('status', Published())->orderBy('id', 'desc')->get();
        $data['stores'] = Store::withCount(['products'])->where('status', Published())->limit($limit)->orderBy('id', 'desc')->get();
        $data['testimonials'] = Testimonial::limit($limit)->orderBy('sort', 'desc')->get();
        $data['store_categories'] = StoreCategory::limit($limit)->orderBy('id', 'desc')->get();
        $data['videos'] = Video::limit($limit)->orderBy('sort', 'desc')->get();
        $data['banners'] = StoreBanner::limit(2)->orderBy('sort', 'desc')->get();

        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
    public function search($term, Request $request)
    {
        $data['products'] = Product::query();
        if($term){
            $data['products'] = $data['products']->where('name', 'LIKE', "%$term%");
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


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Video;
use App\Models\Store;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $limit = ($request->has('limit')) ? $request->limit : 10;
        $data['products'] = Product::limit($limit)->get();
        $data['stores'] = Store::withCount(['products'])->limit($limit)->get();
        $data['testimonials'] = Testimonial::limit($limit)->orderBy('sort', 'desc')->get();
        $data['videos'] = Video::limit($limit)->get();

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

        $data['products'] = $data['products']->paginate($paginate);
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


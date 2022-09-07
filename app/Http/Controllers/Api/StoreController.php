<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;


class StoreController extends Controller
{
    function __construct(Store $store){
        $this->model = $store;
    }
    public function index(Request $request)
    {
        $stores = $this->model->query();
        $paginate = ($request->has('paginate')) ? $request->paginate : 15;
        $stores = $stores->withCount(['products'])->paginate($paginate);
        $data['stores'] = $stores;
        $data['status'] = 200;
        return response()->json($data, $data['status']);
    }
}

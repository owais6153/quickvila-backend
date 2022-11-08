<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Store;
use DataTables;
use Bouncer;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-order', ['index', 'getList', 'show']);
        $this->dir = 'admin.order.';
    }
    public function index(){
        return view($this->dir . 'index');
    }
    public function getList(Request $request)
    {
        $model =  Order::with('customer');
        if(!Bouncer::can('all-orders')){
            $store_ids = Store::where('user_id', auth()->id())->pluck('id');
            $model = $model->whereHas('items', function($query) use($store_ids) {
                $query->whereIn('store_id', $store_ids);
            });
        }

        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('view-order')) {
                    $actionBtn .= '<a href="' . route('order.show', ['order' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function show(Order $order)
    {
        $store_ids = Store::where('user_id', auth()->id())->pluck('id');
        $my_store_items = $order->items()->whereIn('store_id', $store_ids)->get();
        return view($this->dir . 'show', compact('order', 'my_store_items'));
    }
    public function edit()
    {
        abort(404);
    }
    public function update()
    {
        abort(404);
    }
    public function store()
    {
        abort(404);
    }
    public function create()
    {
        abort(404);
    }
    public function destroy()
    {
        abort(404);
    }
}

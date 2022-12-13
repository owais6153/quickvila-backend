<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Order;

class OrderController extends Controller
{
    function __construct(Order $order)
    {
        $this->model = new Repository($order);
    }

    public function activeorders(Request $request)
    {
        $mystore = $request->mystore;
        $orders = $mystore->order_items()->where('is_refund', false)->get();


        $data = [
            'orders' => $orders,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}

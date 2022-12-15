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
        $orders = Order::whereHas('items', function($q) use($mystore){
            $q->where('is_refund', false)->where('store_id', $mystore->id);
        })->where('status', InProcess())->get();

        if($orders->count()){
            $data = [
                'orders' => $orders->items()->with(['variation', 'product'])->where('is_refund', false)->get(),
                'status' => 200
            ];

            return response()->json($data, 200);
        }
        else{
            $data = [
                'message' => 'No order found',
                'status' => 404
            ];

            return response()->json($data, 200);
        }

    }
    public function refund(Request $request, $id)
    {
        $mystore = $request->mystore;
        $item = $mystore->order_items->where('id', $id)->first();
        $item->update([
            'is_refund' => true,
        ]);

        return response()->json(['status'=> 200,'message' => 'Order refunded, It will take aprox 5mins to fully refund.'], 200);
    }
}

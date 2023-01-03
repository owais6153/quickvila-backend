<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Order;
use App\Models\OrderProduct;


class OrderController extends Controller
{
    function __construct(Order $order)
    {
        $this->model = new Repository($order);
    }

    public function neworders(Request $request)
    {

        try {
            $mystore = $request->mystore;
            $orders = Order::with(['customer'])->whereHas('items', function ($q) use ($mystore) {
                $q->where('status', Pending())->where('store_id', $mystore->id);
            })->where('status', InProcess())->get();

            if ($orders->count() > 0) {
                $data = [
                    'orders' => $orders,
                    'status' => 200
                ];

                return response()->json($data, 200);
            } else {
                $data = [
                    'errors' => ['orders' => ['Orderss not found']],
                    'status' => 404
                ];

                return response()->json($data, 404);
            }
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function show(Request $request, $id)
    {
        try {
            $mystore = $request->mystore;
            $order =  Order::with(['customer', 'items' => function ($q) use ($mystore) {
                $q->where('store_id', $mystore->id);
            }, 'items.product', 'items.variation'])->where('id', $id)->whereHas('items', function ($q) use ($mystore) {
                $q->where('store_id', $mystore->id);
            })->first();
            if (empty($order)) {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];
                return response()->json($data, 404);
            } else {
                $data = [
                    'order' => $order,
                    'status' => 200
                ];
                return response()->json($data, 200);
            }
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function complete(Request $request, $id)
    {
        try {
            $mystore = $request->mystore;
            $items = $mystore->order_items()->where('order_id', $id)->get();
            if ($items->count() < 1) {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];
                return response()->json($data, 404);
            } else {
                foreach ($items as $item) {
                    $item->update([
                        'status' => Completed()
                    ]);
                }

                $data = [
                    'message' => 'Order Completed',
                    'status' => 200
                ];
                return response()->json($data, 200);
            }
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function cancel(Request $request, $id)
    {
        try {
            $mystore = $request->mystore;
            $items = $mystore->order_items()->where('order_id', $id)->get();
            if ($items->count() < 1) {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];
                return response()->json($data, 404);
            } else {
                foreach ($items as $item) {
                    $item->update([
                        'status' => Refunded()
                    ]);
                }

                $data = [
                    'message' => 'Order Completed',
                    'status' => 200
                ];
                return response()->json($data, 200);
            }
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }

    public function active(Request $request)
    {
        try {
            $mystore = $request->mystore;
            $orders = Order::with(['customer'])->whereHas('items', function ($q) use ($mystore) {
                $q->where('status', InProcess())->where('store_id', $mystore->id);
            })->where('status', InProcess())->get();
            if ($orders->count() > 0) {
                $data = [
                    'orders' => $orders,
                    'status' => 200
                ];

                return response()->json($data, 200);
            } else {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];

                return response()->json($data, 404);
            }
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function refunded_orders(Request $request)
    {
        try {
            $mystore = $request->mystore;
            $orders = Order::with(['customer'])->whereHas('items', function ($q) use ($mystore) {
                $q->where('status', Refunded())->where('store_id', $mystore->id);
            })->get();
            if ($orders->count() > 0) {
                $data = [
                    'orders' => $orders,
                    'status' => 200
                ];

                return response()->json($data, 200);
            } else {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];

                return response()->json($data, 404);
            }
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $mystore = $request->mystore;
            $orders = Order::with(['customer'])->whereHas('items', function ($q) use ($mystore) {
                $q->where('store_id', $mystore->id);
            })->get();
            if ($orders->count() > 0) {
                $data = [
                    'orders' => $orders,
                    'status' => 200
                ];

                return response()->json($data, 200);
            } else {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];

                return response()->json($data, 404);
            }
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }

    public function refund(Request $request, $id)
    {
        try {
            $mystore = $request->mystore;
            $item = $mystore->order_items->where('id', $id)->first();

            if (empty($item)) {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];

                return response()->json($data, 404);
            }
            $item->update([
                'status' => Refunded(),
            ]);

            return response()->json(['status' => 200, 'message' => 'Order Item refunded, It will take aprox 5mins to fully refund.'], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function accept(Request $request, $id)
    {
        try {
            $mystore = $request->mystore;
            $item = $mystore->order_items->where('id', $id)->first();

            if (empty($item)) {
                $data = [
                    'errors' => ['order' => ['Order not found']],
                    'status' => 404
                ];

                return response()->json($data, 404);
            }
            $item->update([
                'status' => InProcess(),
            ]);

            return response()->json(['status' => 200, 'message' => 'Order Item Accepeted'], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
}

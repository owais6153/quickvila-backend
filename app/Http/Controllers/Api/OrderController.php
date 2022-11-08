<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        return response()->json(['order' => $order, 'status' => 200], 200);
    }
}

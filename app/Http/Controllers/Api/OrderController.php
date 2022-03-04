<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //

    public function index(Request $request) {
        return Order::paginate(10, ['*'], 'page', $request->page);
    }

    public function single(Request $request, Order $order) {
        $response = $order->toArray();
        $response['items'] = $order->products->toArray();
        $response['charges'] = $order->charges->toArray();

        return $response;
    }

    public function createOrder(Request $request) {
        $validator = Validator::make($request->all, [
            ''
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }



    }



}

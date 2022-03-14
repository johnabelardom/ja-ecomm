<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //

    public function index(Request $request) {
        return Order::paginate(10, ['*'], 'page', $request->page);
    }

    public function single(Request $request, $order) {
        $order = Order::where('uid', $order)->firstOrFail();

        $response = $order->toArray();
        $response['items'] = $order->products->toArray();
        $response['charges'] = $order->charges->toArray();

        return $response;
    }

    public function createOrder(Request $request) {
        $validator = Validator::make($request->all(), [
            // 'customer' => 'required|array',
            'customer.firstname' => 'required|max:255',
            'customer.lastname' => 'required|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.address' => 'required|max:255',
            'customer.region' => 'required|max:255',
            'customer.city' => 'required',
            'customer.country' => 'required',
            'customer.zipcode' => '',
            'customer.notes' => 'max:255',
            
            // 'items' => 'required|array',
            // 'items.*.product_id' => 'required|exists:products,id',
            // 'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required',
            
            // 'charges' => 'required|array',
            // 'charges.*' => 'required|array|in:shipping',
            'charges.*.id' => 'required',
            'charges.*.name' => 'required',
            // 'charges.*.quantity' => 'required|integer',
            'charges.*.price' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }


        $order = new Order();
        $order->uid = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
        $order->email = $request->customer['email'];
        $order->firstname = $request->customer['firstname'];
        $order->lastname = $request->customer['lastname'];
        $order->line_1_address = $request->customer['address'];
        $order->line_2_address = ''; //$request->customer['line_2_address'];
        $order->city = $request->customer['city'];
        $order->country = $request->customer['country'];
        $order->zipcode = $request->customer['zipcode'];
        $order->notes = $request->customer['notes'];
        $order->status = 'new'; //$request->customer['status'];
        
        if ($order->save()) {
            $order->refresh();

            $order_items = [];
            $order_charges = [];
            foreach ($request->items as $cs => $item) {
                $order_items[] = [
                    'order_id' => $order->id,
                    'product_id' => $cs,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'type' => 'product',
                    'seller_id' => $item['seller_id'],
                ];
            }
            foreach ($request->charges as $cs => $item) {
                $order_charges[] = [
                    'order_id' => $order->id,
                    // 'product_id' => $cs,
                    'name' => $item['name'],
                    'quantity' => 1, //$item['quantity'],
                    'price' => $item['price'],
                    'type' => 'charge'
                ];
            }

            if (! empty($order_items))
                OrderItem::insert($order_items);

            if (! empty($order_charges))
                OrderItem::insert($order_charges);

            session()->flash('message', 'Order has been placed');
            $this->clearSessionFlow();
            // dd($order);

            $newOrder = $order->toArray();
            $newOrder['items'] = $order->products->toArray();
            $newOrder['charges'] = $order->charges->toArray();

            return $newOrder;
            // return redirect(route('thank-you', $order->uid));
        }

    }

    

    public function clearSessionFlow() {
        /* session()->put('checkout', [
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'country' => '',
            'address' => '',
            'city' => '',
            'zipcode' => '',
            'notes' => '',
        ]); */
        session()->put('cart_charges', [
            'shipping' => config('store.shipping_methods.standard', [
                'id' => 'standard',
                'name' => 'Standard Shipping',
                'price' => 10.00
            ]),
        ]);
        session()->put('cart', []);
    }



}

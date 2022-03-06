<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Component;

class Checkout extends Component
{
    public $cart_session = [];
    public $cart = [];
    public $cart_charges_session = [];
    // public $checkout_session = [];
    public $total = 0.00;
    public $subtotal = 0.00;
    public $shipping_method = 'standard';

    public $checkout_session = [
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'country' => '',
        'address' => '',
        'city' => '',
        'zipcode' => '',
        'notes' => '',
    ];

    // protected $listeners = ['cart.updated' => 'computize'];

    public function render()
    {
        $this->computize();
        return view('livewire.checkout');
    }

    public function __construct() {
        $this->cart_session = $this->cart = session()->get('cart', []);
        // dd($this->cart_session);
        $this->checkout_session = session()->get('checkout', [
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'country' => '',
            'address' => '',
            'city' => '',
            'zipcode' => '',
            'notes' => '',
        ]);
        // dd($this->checkout_session);
        $this->cart_charges_session = session()->get('cart_charges', [
            'shipping' => config('store.shipping_methods.' . $this->shipping_method, [
                'id' => 'standard',
                'name' => 'Standard Shipping',
                'price' => 10.00
            ]),
        ]);
        $this->computize();
    }

    public function computize() {
        $this->totalize();
        $this->subTotalize();
    }

    public function totalize() {
        $this->total = 0.00;
        foreach($this->cart_session as $cs => $item) {
            $this->total += ($item['price'] * $item['quantity']);
        }

        foreach($this->cart_charges_session as $cs => $item) {
            $this->total += ($item['price']);
        }
    }

    public function subTotalize() {
        $this->subtotal = 0.00;
        foreach($this->cart_session as $cs => $item) {
            $this->subtotal += ($item['price'] * $item['quantity']);
        }
    }

    public function updateItem($product_id) {
        // if (! empty($this->cart_session[$product_id])) {
        //     $this->cart_session[$product_id]["quantity"] = $request->quantity;
        //     session()->put('cart', $cart);
        //     session()->flash('success', 'Product removed successfully');
        // }
        session()->put('cart', $this->cart_session);
        session()->flash('success', 'Product removed successfully');
        $this->emit('cart.updated');
    }

    
    public function removeItem($product_id)
    {
        if (! empty($this->cart_session[$product_id])) {
            unset($this->cart_session[$product_id]);
            $this->updateItem($product_id);
            session()->flash('success', 'Product removed successfully');
        }
    }

    public function updateCheckoutSession($key) {
        $checkout_session = session()->get('checkout', [
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'country' => '',
            'address' => '',
            'city' => '',
            'zipcode' => '',
            'notes' => '',
        ]);

        $checkout_session[$key] = ! empty($this->checkout_session[$key]) ? $this->checkout_session[$key] : '';

        session()->put('checkout', $this->checkout_session);
    }

    public function placeOrder() {
        $this->validate([
            'checkout_session' => 'required|array',
            'checkout_session.firstname' => 'required|max:255',
            'checkout_session.lastname' => 'required|max:255',
            'checkout_session.email' => 'required|email|max:255',
            'checkout_session.address' => 'required|max:255',
            'checkout_session.city' => 'required',
            'checkout_session.zipcode' => 'required',
            'checkout_session.notes' => 'string|max:255',
        ]);

        $order = new Order();
        $order->uid = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
        $order->email = $this->checkout_session['email'];
        $order->firstname = $this->checkout_session['firstname'];
        $order->lastname = $this->checkout_session['lastname'];
        $order->line_1_address = $this->checkout_session['address'];
        $order->line_2_address = ''; //$this->checkout_session['line_2_address'];
        $order->city = $this->checkout_session['city'];
        $order->country = $this->checkout_session['country'];
        $order->zipcode = $this->checkout_session['zipcode'];
        $order->notes = $this->checkout_session['notes'];
        $order->status = 'new'; //$this->checkout_session['status'];
        
        if ($order->save()) {
            $order->refresh();

            $order_items = [];
            $order_charges = [];
            foreach ($this->cart_session as $cs => $item) {
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
            foreach ($this->cart_charges_session as $cs => $item) {
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
            return redirect(route('thank-you', $order->uid));
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
            'shipping' => config('store.shipping_methods.' . $this->shipping_method, [
                'id' => 'standard',
                'name' => 'Standard Shipping',
                'price' => 10.00
            ]),
        ]);
        session()->put('cart', []);
    }
}

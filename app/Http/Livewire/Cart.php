<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\View;
use Livewire\Component;

class Cart extends Component
{
    public $cart_session = [];
    public $cart_charges_session = [];
    public $cart = [];
    public $total = 0.00;
    public $subtotal = 0.00;
    public $shipping_method = 'standard';

    // protected $listeners = ['cart.updated' => 'computize'];
    protected $listeners = ['charges.updated' => 'updateCharges'];

    public function render()
    {
        $this->computize();
        return view('livewire.cart');
    }

    public function __construct() {
        $this->cart_session = $this->cart = session()->get('cart', []);
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

    public function addToCart($product_id) {
        $product = Product::findOrFail($product_id);
          
        $cart = session()->get('cart', []);
  
        if(isset($cart[$product_id])) {
            $cart[$product_id]['quantity']++;
        } else {
            $cart[$product_id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "seller_id" => $product->user_id,
            ];
        }
          
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function increaseQuantity($product_id) {
        if(! empty($this->cart_session[$product_id])) {
            $this->cart_session[$product_id]['quantity']++;
            $this->updateItem($product_id);
        }
        $this->emit('cart.updated');
    }

    public function decreaseQuantity($product_id) {
        if(! empty($this->cart_session[$product_id]) && $this->cart_session[$product_id]['quantity'] != 1) {
            $this->cart_session[$product_id]['quantity']--;
            $this->updateItem($product_id);
        }
        $this->emit('cart.updated');
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

    public function updateCharges() {
        $this->cart_charges_session = session()->get('cart_charges', [
            'shipping' => config('store.shipping_methods.' . $this->shipping_method, [
                'id' => 'standard',
                'name' => 'Standard Shipping',
                'price' => 10.00
            ]),
        ]);

        $shipping = config('store.shipping_methods.' . $this->shipping_method, [
            'name' => 'Standard Shipping',
            'price' => 10.00
        ]);
        $this->cart_charges_session['shipping'] = [
            'id' => $shipping['id'],
            "name" => $shipping['name'],
            "quantity" => 1,
            "price" => $shipping['price'],
            // "image" => $product->image
        ];
        
        session()->put('cart_charges', $this->cart_charges_session);

        $this->computize();
    }

    public function checkout() {
        return redirect(route('checkout'));
    }

}

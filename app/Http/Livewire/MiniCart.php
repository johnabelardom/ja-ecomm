<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class MiniCart extends Component
{
    public $cart_session = [];
    public $cart_charges_session = [];
    public $cart = [];
    public $total = 0.00;
    public $subtotal = 0.00;
    public $shipping_method = '';

    protected $listeners = ['cart.updated' => 'checkCart'];

    public function render()
    {
        $this->computize();
        return view('livewire.mini-cart');
    }

    public function __construct() {
        $this->cart_session = $this->cart = session()->get('cart', []);
        $this->checkout_session = session()->get('checkout', []);
        $this->cart_charges_session = session()->get('cart_charges', [
            'shipping' => config('store.shipping_methods.' . $this->shipping_method, [
                'name' => 'Standard Shipping',
                'price' => 10.00
            ]),
        ]);
        $this->computize();
    }

    public function checkCart() {
        if (empty($this->cart_session)) {
            return redirect(route('cart'));
        }
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

}

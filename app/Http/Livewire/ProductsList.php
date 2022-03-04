<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination;
        
    // public $products = [];

    public function render()
    {
        return view('livewire.products-list', [
            'products' => Product::paginate(10),       
        ]);
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
}

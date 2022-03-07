<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    //
    public function config(Request $request) {
        return config('store');
    }

    public function cartAddItem(Request $request, Product $product) {
        $cart = session()->get('cart', []);
  
        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "seller_id" => $product->user_id,
            ];
        }
          
        session()->put('cart', $cart);
        return $cart; //redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function cartGetItems(Request $request) {
        return session()->get('cart', []);
    }

    public function cartGetDetails(Request $request) {
        $cart = session()->get('cart', []);
        $cart_charges = session()->get('cart_charges', [
            'shipping' => config('store.shipping_methods.standard', [
                'id' => 'standard',
                'name' => 'Standard Shipping',
                'price' => 10.00
            ]),
        ]);
        
        $total = 0.00;
        $subtotal = 0.00;
        foreach($cart as $cs => $item) {
            $total += ($item['price'] * $item['quantity']);
            $subtotal += ($item['price'] * $item['quantity']);
        }

        foreach($cart_charges as $cs => $item) {
            $total += ($item['price']);
        }


        return [
            'cart_items' => $cart,
            'cart_charges' => $cart_charges,
            'config' => config('store'),
            'total' => number_format($total, 2),
            'subtotal' => number_format($subtotal, 2),
            'shipping_method' => $cart_charges['shipping']['id'],
        ];
    }

    

    public function cartRemoveItem(Request $request, $product) {
        $cart = session()->get('cart', []);

        if (! empty($cart[$product])) {
            unset($cart[$product]);
            session()->put('cart', $cart);
            session()->flash('success', 'Product removed successfully');
        }

        return $cart;
    }

    public function cartIncreaseItem($product) {
        $cart = session()->get('cart', []);
        if(! empty($cart[$product])) {
            $cart[$product]['quantity']++;
            session()->put('cart', $cart);
        }
        return session()->get('cart', []);
    }

    public function cartDecreaseItem($product) {
        $cart = session()->get('cart', []);
        if(! empty($cart[$product]) && $cart[$product]['quantity'] != 1) {
            $cart[$product]['quantity']--;
            session()->put('cart', $cart);
        }
        return session()->get('cart', []);
    }



    

    public function cartUpdateCharges(Request $request) {
        $validator = Validator::make($request->all(), [
            'shipping_method' => 'required|in:' . implode(',', array_keys(config('store.shipping_methods' )) )
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $cart_charges_session = session()->get('cart_charges', [
            'shipping' => config('store.shipping_methods.standard', [
                'id' => 'standard',
                "name" => 'Standard Shipping',
                "quantity" => 1,
                "price" => 10.00,
                // "image" => $product->image
            ])
        ]);

        $shipping = config('store.shipping_methods.' . $request->shipping_method, [
            'id' => 'standard',
            "name" => 'Standard Shipping',
            "quantity" => 1,
            "price" => 10.00,
        ]);
        $cart_charges_session['shipping'] = [
            'id' => $shipping['id'],
            "name" => $shipping['name'],
            "quantity" => 1,
            "price" => $shipping['price'],
            // "image" => $product->image
        ];
        
        session()->put('cart_charges', $cart_charges_session);

        return  $cart_charges_session;
    }

    public function cartGetCharges(Request $request) {
        return session()->get('cart_charges', [
            'shipping' => config('store.shipping_methods.standard', [
                "name" => 'Standard Shipping',
                "quantity" => 1,
                "price" => 10.00,
                // "image" => $product->image
            ])
        ]);
    }


}

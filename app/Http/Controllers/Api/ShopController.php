<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use EsteIt\ShippingCalculator\Address;
use EsteIt\ShippingCalculator\Calculator\BaseCalculator;
use EsteIt\ShippingCalculator\Dimensions;
use EsteIt\ShippingCalculator\Handler\DhlHandler;
use EsteIt\ShippingCalculator\Package;
use EsteIt\ShippingCalculator\Weight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ShipEngine\ShipEngine;
use ShipEngine\ShipEngineClient;
use ShipEngine\ShipEngineConfig;
use ShipEngine\Message\ShipEngineException;

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

    public function calculateShipping(Request $request) {
        $validator = Validator::make($request->all(), [
            'customer.firstname' => 'required|max:255',
            'customer.lastname' => 'required|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.address' => 'required|max:255',
            'customer.region' => 'required|max:255',
            'customer.city' => 'required',
            'customer.country' => 'required|max:255',
            'customer.zipcode' => 'max:255',
            'customer.notes' => 'max:255',
            
            // 'items' => 'required|array',
            // 'items.*.product_id' => 'required|exists:products,id',
            // 'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
                
        $config = array(
            'apiKey' => 'TEST_oHSGjBa2D/QIWWtxfopJMds7qVeihx5dX5dLWqZXNx8',
            'pageSize' => 75,
            'retries' => 3,
            'timeout' => new \DateInterval('PT15S')
        );
        $shipengine = new ShipEngine($config);

        try {
            //code...
            
            $config = new ShipEngineConfig($config);
            $client = new ShipEngineClient();
            $apiResponse = $client->post(
                'v1/rates/estimate',
                $config,
                [
                    'carrier_ids' => [
                        'se-1960428',
                        'se-1960427',
                        'se-1960429',
                    ],
                    'from_country_code' => 'US',
                    'from_postal_code' => '78756',
                    'to_country_code' => $request->customer['country'],
                    'to_postal_code' => $request->customer['zipcode'],
                    'to_city_locality' => $request->customer['city'],
                    'to_state_province' => $request->customer['region'],
                    'weight' => [
                        'value' => (function($items) {
                            $total = 0;
                            foreach($items as $i => $item) {
                                $total += ($item['quantity']);
                            }

                            return $total;
                        })($request->items),
                        'unit' => 'ounce',
                    ],
                    'dimensions' => [
                        'unit' => 'inch',
                        'length' => 5.0,
                        'width' => 5.0,
                        'height' => 5.0,
                    ],
                    'confirmation' => 'none',
                    'address_residential_indicator' => 'no',
                ]
            );

            return $apiResponse;
        } catch (ShipEngineException $th) {
            return $th;
        }
        // return $shipengine->listCarriers();

    }


}

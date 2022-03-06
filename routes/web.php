<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::get('/cart', function () {
    return view('frontend.cart');
})->name('cart');

Route::get('/checkout', function () {
    if (empty(session()->get('cart', [])))
        return redirect(route('cart'));

    return view('frontend.checkout');
})->name('checkout');

Route::get('/thank-you/{order}', function ($order_uid) {
    $order = Order::where('uid', $order_uid)->first();

    if (! $order)
        abort(404);

    return view('frontend.thank-you', compact('order'));
})->name('thank-you');

Route::middleware(['auth:sanctum', 'verified'])->get('/account', function () {
    return view('dashboard');
})->name('account');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $orders = auth()->user()->getOrders()->paginate(10);
    return view('dashboard', compact('orders'));
})->name('dashboard');


// vue
Route::group(['prefix' => 'vue', 'as' => 'vue.'], function() {
    Route::get('/', function () {
        return view('frontend-vue.home');
    })->name('home');
    
    Route::get('/cart', function () {
        return view('frontend-vue.cart');
    })->name('cart');
    
    Route::get('/checkout', function () {
        if (empty(session()->get('cart', [])))
            return redirect(route('cart'));
    
        return view('frontend-vue.checkout');
    })->name('checkout');
});

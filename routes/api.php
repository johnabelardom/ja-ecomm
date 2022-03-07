<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('cart/add-item/{product}', [Api\ShopController::class, 'cartAddItem']);
Route::post('cart/increase-item/{product}', [Api\ShopController::class, 'cartIncreaseItem']);
Route::post('cart/decrease-item/{product}', [Api\ShopController::class, 'cartDecreaseItem']);
Route::get('cart', [Api\ShopController::class, 'cartGetItems']);
Route::get('cart/details', [Api\ShopController::class, 'cartGetDetails']);
Route::delete('cart/remove-item/{product}', [Api\ShopController::class, 'cartRemoveItem']);

Route::put('cart/charges/', [Api\ShopController::class, 'cartUpdateCharges']);
Route::get('cart/charges', [Api\ShopController::class, 'cartGetCharges']);
// Route::delete('cart/remove-item/{product}', [Api\ShopController::class, 'cartRemoveItem']);


Route::get('/config', [Api\ShopController::class, 'config']);
Route::get('/products', [Api\ProductController::class, 'index']);

Route::get('/orders', [Api\OrderController::class, 'index']);
Route::get('/orders/{order}', [Api\OrderController::class, 'single']);
Route::post('/order', [Api\OrderController::class, 'createOrder']);

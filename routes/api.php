<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\OrdersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/products', [ProductsController::class, 'getAllProducts']);
Route::get('/product/{id}', [ProductsController::class, 'getProduct']);

Route::get('/showorders/{category}', [OrdersController::class, 'publicGetAllOrders']);
Route::get('/showorders/{id}', [OrdersController::class, 'publicGetOrder']);
Route::put('/mark-as-shipped/{id}', [OrdersController::class, 'markAsShipped']);
Route::put('/cancel-order/{id}', [OrdersController::class, 'cancelOrder']);
Route::put('/complete-order/{id}', [OrdersController::class, 'completedOrder']);
Route::get('/showorders/toship', [OrdersController::class, 'publicGetAllOrders']);
Route::get('/showorders/toreceive', [OrdersController::class, 'publicGetAllOrders']);
Route::get('/showorders/completed', [OrdersController::class, 'publicGetAllOrders']);
Route::get('/showorders/cancelled', [OrdersController::class, 'publicGetAllOrders']);

Route::post('/add-products', [ProductsController::class, 'store']);
Route::post('/store-products', [ProductsController::class, 'store']);

Route::put('/update-product/{id}', [ProductsController::class, 'update']);
Route::get('/delete-product/{id}', [ProductsController::class, 'destroy']);

Route::post('/stripe/create-checkout-session', [StripePaymentController::class, 'createCheckoutSession']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/update-name', [AuthController::class, 'updateName']);
    Route::middleware('auth:sanctum')->put('/update-password', [AuthController::class, 'updatePassword']);
    Route::put('/update-address', [AuthController::class, 'updateAddress']);

    Route::get('/orders', [OrdersController::class, 'getAllOrders']);
    Route::get('/order/{id}', [OrdersController::class, 'getOrder']);
    Route::get('/toshiporders', [OrdersController::class, 'toShipOrders']);
    Route::get('/toreceiveorders', [OrdersController::class, 'toReceiveOrders']);
    Route::get('/completedorders', [OrdersController::class, 'completedOrders']);
});
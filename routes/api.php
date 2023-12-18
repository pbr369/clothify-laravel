<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;

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

Route::get('/add-products', [ProductsController::class, 'create']);
Route::post('/store-products', [ProductsController::class, 'store']);

Route::put('/update-product/{id}', [ProductsController::class, 'update']);
Route::get('/delete-product/{id}', [ProductsController::class, 'destroy']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/update-name', [AuthController::class, 'updateName']);
    Route::middleware('auth:sanctum')->put('/update-password', [AuthController::class, 'updatePassword']);
    Route::put('/update-address', [AuthController::class, 'updateAddress']);
});
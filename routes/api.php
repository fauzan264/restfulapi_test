<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth
Route::get('/auth', [AuthController::class, "auth"]);

// Product
Route::controller(ProductController::class)->group(function() {
    Route::get('/product', "findAll");
    Route::get('/product/{product}', "findOne");
});

// Order
Route::controller(OrderController::class)->group(function() {
    Route::post('/order', "store");
    Route::get('/order', "findAll");
    Route::patch('/order/{order}', "update");
    Route::delete('order/{order}', "delete");
});
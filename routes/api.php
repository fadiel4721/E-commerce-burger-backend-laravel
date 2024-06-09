<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\UkuranController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\DetailProductController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\ProductCustController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Group Auth Sanctum
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

});

//Group Auth San]ctum Admin
Route::group(['middleware'=> ['auth:sanctum', 'isAdmin'], 'prefix' => 'admin'], function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
   
    
    
    Route::get('/ukuran', [UkuranController::class, 'index']);
    Route::post('/ukuran', [UkuranController::class, 'store']);
    Route::get('/ukuran/{id}', [UkuranController::class, 'show']);
    Route::post('/ukuran/{id}', [UkuranController::class, 'update']);
    Route::delete('/ukuran/{id}', [UkuranController::class, 'destroy']);
    
    
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    
});

Route::group(['middleware' => ['auth:sanctum', 'isCustomer']], function () {
    Route::get('/keranjang', [KeranjangController::class, 'index']);
    Route::get('/keranjang/{id}', [KeranjangController::class, 'show']);
    Route::post('/keranjang', [KeranjangController::class, 'store']);
    Route::patch('/keranjang/{id}', [KeranjangController::class, 'update']);
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy']);
    Route::post('/checkout', [CheckoutController::class, 'store']);
    Route::get('/checkout', [CheckoutController::class, 'index']);

    Route::get('/detail_products', [DetailProductController::class, 'index']);
    Route::get('/detail_products/{id}', [DetailProductController::class, 'show']);
    Route::post('/detail_products', [DetailProductController::class, 'store']);
    Route::put('/detail_products/{id}', [DetailProductController::class, 'update']);
    Route::delete('/detail_products/{id}', [DetailProductController::class, 'destroy']);
    



});




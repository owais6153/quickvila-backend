<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Store\StoreController;
use App\Http\Controllers\Api\Store\ProductController;
use App\Http\Controllers\Api\Store\CategoryController;
use App\Http\Controllers\Api\Store\AttributeController;
use App\Http\Controllers\Api\Store\AttributeOptionController;
use App\Http\Controllers\Api\Store\OrderController;





Route::middleware(['auth:sanctum', 'iAmStoreOwner'])->group(function () {
    Route::get('', [StoreController::class, 'index']);
    Route::post('update', [StoreController::class, 'update']);

    // Attributes
    Route::get('attributes', [AttributeController::class, 'index']);
    Route::post('attributes/create', [AttributeController::class, 'create']);
    Route::get('attributes/{attribute:id}', [AttributeController::class, 'show']);
    Route::post('attributes/{attribute:id}/update', [AttributeController::class, 'update']);
    Route::delete('attributes/{attribute:id}/destroy', [AttributeController::class, 'destroy']);

    // Options
    Route::get('attributes/{attribute:id}/options', [AttributeOptionController::class, 'index']);
    Route::post('attributes/{attribute:id}/options/create', [AttributeOptionController::class, 'create']);
    Route::get('attributes/{attribute:id}/options/{attributeoption:id}', [AttributeOptionController::class, 'show']);
    Route::post('attributes/{attribute:id}/options/{attributeoption:id}/update', [AttributeOptionController::class, 'update']);
    Route::delete('attributes/{attribute:id}/options/{attributeoption:id}/destroy', [AttributeOptionController::class, 'destroy']);

    // Categories
    Route::get('products/categories', [CategoryController::class, 'index']);
    Route::post('products/categories/create', [CategoryController::class, 'create']);
    Route::get('products/categories/{productcategory:id}', [CategoryController::class, 'show']);
    Route::put('products/categories/{productcategory:id}/update', [CategoryController::class, 'update']);
    Route::delete('products/categories/{productcategory:id}/destroy', [CategoryController::class, 'destroy']);

    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products/create', [ProductController::class, 'create']);
    Route::get('products/{product:id}', [ProductController::class, 'show']);
    Route::post('products/{product:id}/update', [ProductController::class, 'update']);
    Route::delete('products/{product:id}/destroy', [ProductController::class, 'destroy']);

    Route::post('products/get-possible-variations', [ProductController::class, 'listForVariations']);


    // Orders

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/refunded', [OrderController::class, 'refunded_orders']);
    Route::get('orders/new', [OrderController::class, 'neworders']);
    Route::get('orders/active', [OrderController::class, 'active']);
    Route::get('orders/{order:id}', [OrderController::class, 'show']);
    Route::get('orders/search/{orderno}', [OrderController::class, 'search']);
    Route::get('orders/{order:id}/complete', [OrderController::class, 'complete']);
    Route::get('orders/{order:id}/cancel', [OrderController::class, 'cancel']);
    Route::get('orders/items/{id}/refund', [OrderController::class, 'refund']);
    Route::get('orders/items/{id}/accept', [OrderController::class, 'accept']);
    Route::get('orders/items/{id}/refund-accepted', [OrderController::class, 'refund_completed_item']);
});

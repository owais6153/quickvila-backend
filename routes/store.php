<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Store\StoreController;
use App\Http\Controllers\Api\Store\ProductController;
use App\Http\Controllers\Api\Store\CategoryController;





Route::middleware(['auth:sanctum', 'iAmStoreOwner'])->group(function () {
    Route::get('', [StoreController::class, 'index']);
    Route::post('update', [StoreController::class, 'update']);

    Route::get('products/categories', [CategoryController::class, 'index']);
    Route::post('products/categories/create', [CategoryController::class, 'create']);
    Route::get('products/categories/{productcategory:id}', [CategoryController::class, 'show']);
    Route::put('products/categories/{productcategory:id}/update', [CategoryController::class, 'update']);
    Route::delete('products/categories/{productcategory:id}/destroy', [CategoryController::class, 'destroy']);

    Route::get('products', [ProductController::class, 'index']);
    Route::post('products/create', [ProductController::class, 'create']);
    Route::get('products/{product:id}', [ProductController::class, 'show']);
    Route::post('products/{product:id}/update', [ProductController::class, 'update']);
    Route::delete('products/{product:id}/destroy', [ProductController::class, 'destroy']);

    Route::get('products/get-possible-variations', [ProductController::class, 'listForVariations']);


});


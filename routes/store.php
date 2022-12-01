<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Store\StoreController;
use App\Http\Controllers\Api\Store\ProductController;





Route::middleware(['auth:sanctum', 'iAmStoreOwner'])->group(function () {
    Route::get('', [StoreController::class, 'index']);
    Route::post('update', [StoreController::class, 'update']);

    Route::get('products', [ProductController::class, 'index']);
});


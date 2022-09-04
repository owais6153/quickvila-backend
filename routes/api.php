<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CartController;
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
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/search', [HomeController::class, 'search']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/logout', [AuthController::class, 'signout']);

        Route::get('/cart', [CartController::class, 'index']);
        Route::put('/cart/add/{product:id}', [CartController::class, 'add']);
        Route::put('/cart/update/{cartProduct:id}/{operation}', [CartController::class, 'update']);
        Route::delete('/cart/remove/{cartProduct:id}', [CartController::class, 'remove']);
        Route::delete('/cart/empty', [CartController::class, 'emptyCart']);
    });



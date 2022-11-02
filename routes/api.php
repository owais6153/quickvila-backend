<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
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
    Route::get('/search/{term}', [HomeController::class, 'search']);
    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/stores/{store:id}', [StoreController::class, 'show']);
    Route::get('/stores/{store:id}/products', [ProductController::class, 'storeProducts']);
    Route::get('/stores/{store:id}/products/{product:id}', [ProductController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);

    // Auth
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forget', [AuthController::class, 'forget'])->middleware('throttle:3,10');
    Route::post('/forget/code-verify', [AuthController::class, 'forgetCodeVerify'])->middleware('throttle:3,10');


    // Authenticated Users Only
    Route::middleware(['auth:sanctum'])->group(function () {
        // Auth
        Route::post('/forget/update', [AuthController::class, 'forgetUpdatePwd']);
        Route::post('/verify', [AuthController::class, 'verify'])->middleware('throttle:3,10');
        Route::get('/code/resend', [AuthController::class, 'resend'])->middleware('throttle:3,10');
        Route::get('/logout', [AuthController::class, 'signout']);
        // Cart
        Route::get('/cart', [CartController::class, 'index']);
        Route::put('/cart/add/{product:id}', [CartController::class, 'add']);
        Route::put('/cart/update/{cartProduct:id}/{operation}', [CartController::class, 'update']);
        Route::delete('/cart/remove/{cartProduct:id}', [CartController::class, 'remove']);
        Route::delete('/cart/empty', [CartController::class, 'emptyCart']);
        // Checkout
        Route::post('/checkout', [CheckoutController::class, 'checkout']);
        //Account

        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/account', [CartController::class, 'index']);
        Route::get('/account/password/update', [CartController::class, 'index']);
        Route::get('/account/profile/update', [CartController::class, 'index']);
        Route::get('/account/orders', [CartController::class, 'index']);
        Route::post('/account/orders/{order:id}/', [CartController::class, 'index']);
        Route::get('/stores/{store:id}/follow', [ProductController::class, 'index']);
        Route::get('/account/following', [CartController::class, 'index']);
    });



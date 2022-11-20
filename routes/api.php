<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\StoreCategoryController;
use App\Http\Controllers\Api\ProductCategoryController;
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
    // Views
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/search/{term}', [HomeController::class, 'search']);

    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/stores/{store:id}', [StoreController::class, 'show']);
    Route::get('/stores/{store:id}/products', [ProductController::class, 'storeProducts']);
    Route::get('/stores/{store:id}/products/{product:id}', [ProductController::class, 'show']);

    Route::get('categories/stores/', [StoreCategoryController::class, 'index']);
    Route::post('categories/stores/', [StoreCategoryController::class, 'stores']);

    Route::post('categories/products/', [ProductCategoryController::class, 'products']);
    Route::get('categories/products/', [ProductCategoryController::class, 'index']);

    Route::get('/products', [ProductController::class, 'index']);




    // Order Detail
    Route::get('/orders/{order:id}', [OrderController::class, 'show']);

    // Auth
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forget', [AuthController::class, 'forget'])->middleware('throttle:5,10');

    // Authenticated Users Only
    Route::middleware(['auth:sanctum'])->group(function () {
        // Auth
        Route::post('/forget/code-verify', [AuthController::class, 'forgetCodeVerify'])->middleware('throttle:5,10');
        Route::post('/forget/update', [AuthController::class, 'forgetUpdatePwd']);
        Route::post('/verify', [AuthController::class, 'verify'])->middleware('throttle:5,10');
        Route::get('/code/resend', [AuthController::class, 'resend'])->middleware('throttle:5,10');
        Route::get('/logout', [AuthController::class, 'signout']);

        // Checkout
        Route::post('/checkout', [CheckoutController::class, 'checkout']);

        //Account
        Route::get('/me', [AccountController::class, 'me']);
        Route::post('/account/update', [AccountController::class, 'update']);
        Route::get('/orders', [CartController::class, 'index']);


        // Route::get('/account', [CartController::class, 'index']);
        // Route::get('/account/password/update', [CartController::class, 'index']);
        // Route::get('/account/profile/update', [CartController::class, 'index']);
        // Route::get('/stores/{store:id}/follow', [ProductController::class, 'index']);
        // Route::get('/account/following', [CartController::class, 'index']);

        // GeoLocations & Adresses
        // Route::put('/geolocation', [AddressController::class, 'setGeoLocation']);

    });



    // Authenticated & Guest both can use these routes
    Route::middleware(['auth.sanctum.optional'])->group(function () {
        // Cart
        Route::get('/cart', [CartController::class, 'index']);
        Route::put('/cart/add/{product:id}', [CartController::class, 'add']);
        Route::put('/cart/update/{cartProduct:id}/{operation}', [CartController::class, 'update']);
        Route::delete('/cart/remove/{cartProduct:id}', [CartController::class, 'remove']);
        Route::delete('/cart/empty', [CartController::class, 'emptyCart']);
    });


    Route::any('{any}', function(){
        $error['errors'] = ['route' => ["API route or method is not valid."]];
        $error['status'] = 404;
        return response()->json($error, 404);
    })->where('any', '.*');

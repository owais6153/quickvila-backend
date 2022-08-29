<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Admin\StoreController;
use App\Http\Controllers\Web\Admin\ProductController;
/*
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Static Pages
Route::get('/', function () {
    return redirect()->route('/admin');
});
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');



// Authentication
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Area
Route::middleware(['auth', 'permission:admin-area'])->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    // Stores
    Route::resource('store', StoreController::class);
    Route::get('store-list', [StoreController::class, 'getList'])->name('store.list');
    // StoreCategory
    Route::resource('storecategory', CategoryController::class);

    // Products
    Route::resource('product', ProductController::class);
    Route::get('product-list', [ProductController::class, 'getList'])->name('product.list');
});

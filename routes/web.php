<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Admin\StoreController;
use App\Http\Controllers\Web\Admin\ProductController;
use App\Http\Controllers\Web\Admin\StoreCategoryController;
use App\Http\Controllers\Web\Admin\ProductCategoryController;
use App\Http\Controllers\Web\Admin\TestimonialController;
use App\Http\Controllers\Web\Admin\VideoController;
use App\Http\Controllers\Web\Admin\SettingController;
use App\Http\Controllers\Web\Admin\OrderController;
use App\Http\Controllers\Web\Admin\UserController;


// Static Pages
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

// Authentication
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Area
Route::middleware(['auth', 'permission:admin-area'])->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('admin.dashboard');

    // Stores
    Route::resource('store', StoreController::class);
    Route::get('store-list', [StoreController::class, 'getList'])->name('store.list');
    Route::get('store/{store:id}/setting', [StoreController::class, 'setting'])->name('store.setting');
    Route::put('store/{store:id}/setting/update', [StoreController::class, 'updateSetting'])->name('store.setting.update');

    // StoreCategory
    Route::resource('storecategory', StoreCategoryController::class);
    Route::get('storecategory-list', [StoreCategoryController::class, 'getList'])->name('storecategory.list');



    // Products
    Route::get('store/{store:id}/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('store/{store:id}/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('store/{store:id}/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('store/{store:id}/product/{product:id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('store/{store:id}/product/{product:id}/update', [ProductController::class, 'update'])->name('product.update');
    Route::delete('store/{store:id}/product/{product:id}/destroy', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('product-list', [ProductController::class, 'getList'])->name('product.list');


    // Route::resource('product', ProductController::class);
    // Route::get('store/{store:id}/products', [ProductController::class, 'store_products'])->name('store.products');

    // ProductCategory
    Route::resource('productcategory', ProductCategoryController::class);
    Route::get('productcategory-list', [ProductCategoryController::class, 'getList'])->name('productcategory.list');

    // Testimonail
    Route::resource('testimonial', TestimonialController::class);
    Route::get('testimonial-list', [TestimonialController::class, 'getList'])->name('testimonial.list');    // Testimonail
    //Video
    Route::resource('video', VideoController::class);
    Route::get('video-list', [VideoController::class, 'getList'])->name('video.list');

    // Settings
    Route::get('setting/{key}', [SettingController::class,  'index'])->name('setting.index');
    Route::post('setting/store', [SettingController::class,  'store'])->name('setting.store');

    // Order
    Route::resource('order', OrderController::class);
    Route::get('order-list', [OrderController::class, 'getList'])->name('order.list');


    Route::resource('user', UserController::class);
    Route::get('user-list', [UserController::class, 'getList'])->name('user.list');

});

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
use App\Http\Controllers\Web\Admin\AttributesController;
use App\Http\Controllers\Web\Admin\AttributeOptionController;
use App\Http\Controllers\Web\Admin\StoreBannerController;
use App\Http\Controllers\FCMController;


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

    Route::post('register-token', [FCMController::class, 'registerToken'])->name('register-token');
    Route::get('send', [FCMController::class, 'sendNotification'])->name('send');



    Route::get('/', [AuthController::class, 'index'])->name('admin.dashboard');

    // Stores
    Route::resource('store', StoreController::class);
    Route::get('store-list', [StoreController::class, 'getList'])->name('store.list');

        // Store Settings
        Route::get('store/{store:id}/setting', [StoreController::class, 'setting'])->name('store.setting');
        Route::put('store/{store:id}/setting/update', [StoreController::class, 'updateSetting'])->name('store.setting.update');

        // StoreCategory
        Route::resource('storecategory', StoreCategoryController::class);
        Route::get('storecategory-list', [StoreCategoryController::class, 'getList'])->name('storecategory.list');

        // Products
        Route::resource('store/{store:id}/product', ProductController::class);
        Route::get('product-list', [ProductController::class, 'getList'])->name('product.list');
        Route::post('product/variations', [ProductController::class, 'listForVariations'])->name('product.variations');

        // ProductCategory
        Route::resource('store/{store:id}/productcategory', ProductCategoryController::class);
        Route::get('store/{store:id}/productcategory-list', [ProductCategoryController::class, 'getList'])->name('productcategory.list');

        // Store Banners
        Route::resource('storebanner', StoreBannerController::class);
        Route::get('storebanner-list', [StoreBannerController::class, 'getList'])->name('storebanner.list');


    // Attributes
    Route::resource('store/{store:id}/attribute', AttributesController::class);
    Route::get('store/{store:id}/attribute-list', [AttributesController::class, 'getList'])->name('attribute.list');
    Route::get('store/{store:id}/attribute-dropdown', [AttributesController::class, 'attributesDropdown'])->name('attribute.dropdown');

    // Attributes Options
    Route::get('attribute/{attribute:id}/options', [AttributeOptionController::class, 'index'])->name('attributeoption.index');
    Route::get('attribute/{attribute:id}/options/create', [AttributeOptionController::class, 'create'])->name('attributeoption.create');
    Route::post('attribute/{attribute:id}/options/store', [AttributeOptionController::class, 'store'])->name('attributeoption.store');
    Route::get('attribute/{attribute:id}/options/{attributeOption:id}/edit', [AttributeOptionController::class, 'edit'])->name('attributeoption.edit');
    Route::put('attribute/{attribute:id}/options/{attributeOption:id}/update', [AttributeOptionController::class, 'update'])->name('attributeoption.update');
    Route::delete('attribute/{attribute:id}/options/{attributeOption:id}/destroy', [AttributeOptionController::class, 'destroy'])->name('attributeoption.destroy');
    Route::get('attribute/{attribute:id}/options-list', [AttributeOptionController::class, 'getList'])->name('attributeoption.list');


    // Testimonail
    Route::resource('testimonial', TestimonialController::class);
    Route::get('testimonial-list', [TestimonialController::class, 'getList'])->name('testimonial.list');    // Testimonail
    //Video
    Route::resource('video', VideoController::class);
    Route::get('video-list', [VideoController::class, 'getList'])->name('video.list');

    // Order
    Route::resource('order', OrderController::class);
    Route::get('order-list', [OrderController::class, 'getList'])->name('order.list');

    Route::resource('user', UserController::class);
    Route::get('user-list', [UserController::class, 'getList'])->name('user.list');
    Route::get('users/verfication-requests', [UserController::class, 'verficationRequests'])->name('user.requests');
    Route::get('users/verfication-requests/get', [UserController::class, 'verficationRequestsGet'])->name('user.requests.get');
    Route::get('users/{user:id}/accept', [UserController::class, 'acceptRequest'])->name('user.requests.accept');
    Route::get('users/{user:id}/cancel', [UserController::class, 'cancelRequest'])->name('user.requests.cancel');

    // Settings
    Route::get('setting/{key}', [SettingController::class,  'index'])->name('setting.index');
    Route::post('setting/store', [SettingController::class,  'store'])->name('setting.store');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

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
    return view('welcome');
});
Route::get('/login', function(){
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/register', function(){
    return view('auth.register');
})->middleware('guest')->name('register');

// Authentication
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::post('/forget-password', [AuthController::class, 'forgetPasswordEmail'])->name('forget-password')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPaassword'])->middleware(['guest'])->name('password.reset');
Route::post('/reset-password',[AuthController::class, 'paasswordUpdate'])->middleware(['guest'])->name('password.update');
Route::post('/register',[AuthController::class, 'register'])->middleware(['guest'])->name('signup');
Route::get('/verification', [AuthController::class, 'verificationNotice'])->name('verification.notice')->middleware(['auth']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, '__invoke'])
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// Admin Area
Route::get('/admin', function(){
    return view('admin.index');
});


// User Area

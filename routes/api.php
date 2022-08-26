<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::namespace('App\Http\Controllers\Api')->group(function () {
    Route::post('/authenticate', [AuthController::class, 'authenticate']);

    Route::get('check', function(){
         return response()->json(['not work'], 200);
    })->middleware('auth:sanctum');
// });

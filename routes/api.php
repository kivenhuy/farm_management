<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['prefix' => 'v2/auth'], function () {
    Route::post('login', 'App\Http\Controllers\Api\V2\AuthController@login');
    Route::post('signup', 'App\Http\Controllers\Api\V2\AuthController@signup');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', 'App\Http\Controllers\Api\V2\AuthController@logout');
        Route::get('dashboard', 'App\Http\Controllers\Api\V2\AuthController@dashboard');
    });
});


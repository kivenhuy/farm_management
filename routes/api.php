<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FarmersController;

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

Route::post('farmer/registration',[App\Http\Controllers\Api\FarmersController::class,'registration']);

//Farm Land



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
    
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', 'App\Http\Controllers\Api\AuthController@logout');
    Route::get('dashboard', 'App\Http\Controllers\Api\AuthController@dashboard');
    
    // Farmer Details
    Route::get('farmer',[App\Http\Controllers\Api\FarmersController::class,'index']);
    Route::get('farmer/{id}',[App\Http\Controllers\Api\FarmersController::class,'show']);


    // Farm land
    Route::get("/farmland", [App\Http\Controllers\Api\FarmLandController::class, 'index'])->name('farmland.index');
    Route::post("/add_farmland", [App\Http\Controllers\Api\FarmLandController::class, 'store'])->name('commnue.add_farmland');

    //Country
    Route::get("/country", [App\Http\Controllers\Api\CountryController::class, 'index'])->name('country.index');

    //Province
    Route::get("/province", [App\Http\Controllers\Api\ProvinceController::class, 'index'])->name('province.index');
    Route::get("/province_filter_by_country/{id}", [App\Http\Controllers\Api\ProvinceController::class, 'filter_by_country'])->name('province.filter_by_country');

    //District
    Route::get("/district", [App\Http\Controllers\Api\DistrictController::class, 'index'])->name('district.index');
    Route::get("/district_filter_by_province/{id}", [App\Http\Controllers\Api\DistrictController::class, 'filter_by_province'])->name('district.filter_by_province');

    //Commune
    Route::get("/commune", [App\Http\Controllers\Api\CommuneController::class, 'index'])->name('commune.index');
    Route::get("/commune_filter_by_district/{id}", [App\Http\Controllers\Api\CommuneController::class, 'filter_by_district'])->name('commune.filter_by_district');


});


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
    Route::get('farmer/drop_down_for_register',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_register']);
    Route::get('farmer/drop_down_for_family_info',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_family_info']);
    Route::get('farmer/drop_down_for_asset_info',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_asset_info']);
    Route::get('farmer/drop_down_for_bank_info',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_bank_info']);
    Route::get('farmer/drop_down_for_finance_info',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_finance_info']);
    Route::get('farmer/drop_down_for_insurance_info',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_insurance_info']);
    Route::get('farmer/drop_down_for_animal_husbandry',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_animal_husbandry']);
    Route::get('farmer/drop_down_for_farm_equipment',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_farm_equipment']);
    Route::get('farmer/{id}',[App\Http\Controllers\Api\FarmersController::class,'show']);

    // Route::put('farmer/drop_down_for_register',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_register']);
    Route::put('farmer/update_family_info',[App\Http\Controllers\Api\FarmersController::class,'update_family_info']);
    Route::put('farmer/update_asset_info',[App\Http\Controllers\Api\FarmersController::class,'update_asset_info']);
    Route::put('farmer/update_bank_info',[App\Http\Controllers\Api\FarmersController::class,'update_bank_info']);
    Route::put('farmer/update_finance_info',[App\Http\Controllers\Api\FarmersController::class,'update_finance_info']);
    Route::put('farmer/update_insurance_info',[App\Http\Controllers\Api\FarmersController::class,'update_insurance_info']);
    Route::put('farmer/update_animal_husbandry',[App\Http\Controllers\Api\FarmersController::class,'update_animal_husbandry']);
    Route::put('farmer/update_farm_equipment',[App\Http\Controllers\Api\FarmersController::class,'update_farm_equipment']);


    // Farm land
    Route::get("/farmland", [App\Http\Controllers\Api\FarmLandController::class, 'index'])->name('farmland.index');
    Route::get("/farmland/dropdown_value", [App\Http\Controllers\Api\FarmLandController::class, 'create'])->name('farmland.create');
    Route::post("/add_farmland", [App\Http\Controllers\Api\FarmLandController::class, 'store'])->name('commnue.add_farmland');


    //Crops Enrollments
    Route::get("/crops", [App\Http\Controllers\Api\CropsController::class, 'index'])->name('crops.index');
    Route::get("/crops/{id}", [App\Http\Controllers\Api\CropsController::class, 'show'])->name('crops.show');
    Route::post("/add_crops", [App\Http\Controllers\Api\CropsController::class, 'store'])->name('crops.add_crops');

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


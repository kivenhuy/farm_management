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



//Farm Land



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
    
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', 'App\Http\Controllers\Api\AuthController@logout');
    
    
    Route::controller(FarmersController::class)->group(function () {

        
        Route::group(['middleware' => 'staff'], function () {
            // Farmer Details
            Route::get('farmer','index');
            Route::get('farmer/drop_down_for_register','drop_down_for_register');
            Route::get('farmer/get_data_for_family_info/{id}','get_data_for_family_info');
            Route::get('farmer/get_data_for_asset_info/{id}','get_data_for_asset_info');
            Route::get('farmer/get_data_for_bank_info/{id}','get_data_for_bank_info');
            Route::get('farmer/get_data_for_finance_info/{id}','get_data_for_finance_info');
            Route::get('farmer/get_data_for_insurance_info/{id}','get_data_for_insurance_info');
            Route::get('farmer/get_data_for_animal_husbandry/{id}','get_data_for_animal_husbandry');
            Route::get('farmer/get_data_for_farm_equipment/{id}','get_data_for_farm_equipment');
            Route::get('farmer/get_data_for_certificate_info/{id}','get_data_for_certificate_info');
            Route::get('farmer/{id}','show');

            // Farmer Register 
            Route::post('farmer/registration','registration');

            // Farmer Update 
            Route::put('farmer/update_family_info/{id}','update_family_info');
            Route::put('farmer/update_asset_info/{id}','update_asset_info');
            Route::put('farmer/update_bank_info/{id}','update_bank_info');
            Route::put('farmer/update_finance_info/{id}','update_finance_info');
            Route::put('farmer/update_insurance_info/{id}','update_insurance_info');
            Route::put('farmer/update_animal_husbandry/{id}','update_animal_husbandry');
            Route::put('farmer/update_farm_equipment/{id}','update_farm_equipment');
            Route::put('farmer/update_certificate/{id}','update_certificate');
            Route::post('farmer/update_personal_info','update_personal_info');
        });

        
        

        // Route::put('farmer/drop_down_for_register',[App\Http\Controllers\Api\FarmersController::class,'drop_down_for_register');
        
         // Farm land
        Route::get("/farmland", [App\Http\Controllers\Api\FarmLandController::class, 'index'])->name('farmland.index');
        Route::get("/get_all_farm_land/{id}", [App\Http\Controllers\Api\FarmLandController::class, 'get_all_farm_land'])->name('get_all_farm_land.index');
        Route::get("/farmland/get_details/{id}", [App\Http\Controllers\Api\FarmLandController::class, 'show'])->name('farmland.show');
        Route::post("/farmland/update_farmland/{id}", [App\Http\Controllers\Api\FarmLandController::class, 'update'])->name('farmland.update');
        Route::get("/farmland/dropdown_value", [App\Http\Controllers\Api\FarmLandController::class, 'create'])->name('farmland.create');
        Route::post("/add_farmland", [App\Http\Controllers\Api\FarmLandController::class, 'store'])->name('commnue.add_farmland');

        //Crops Enrollments
        Route::get("/crops", [App\Http\Controllers\Api\CultivationsController::class, 'index'])->name('crops.index');
        Route::get("/crops_details/{id}", [App\Http\Controllers\Api\CultivationsController::class, 'show'])->name('crops.show');
        Route::get("/crops/get_dropdown", [App\Http\Controllers\Api\CultivationsController::class, 'create'])->name('crops.create');
        Route::post("/crops/update_crops/{id}", [App\Http\Controllers\Api\CultivationsController::class, 'update'])->name('crops.update');
        Route::post("/add_crops", [App\Http\Controllers\Api\CultivationsController::class, 'store'])->name('crops.add_crops');
        Route::get("/crops/get_crop_variety/{id}", [App\Http\Controllers\Api\CultivationsController::class, 'get_crop_variety'])->name('crops.get_crop_variety');

        // Dashboard
        Route::get('dashboard', 'App\Http\Controllers\Api\AuthController@dashboard');
        
    });
    
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


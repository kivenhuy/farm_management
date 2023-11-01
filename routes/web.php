<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SeasonMasterController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FarmersController;
use App\Http\Controllers\Admin\CropMasterController;
use App\Http\Controllers\CatalogueValueController;
use App\Http\Controllers\FarmLandController;
use App\Http\Controllers\LogActivitiesController;
use App\Models\CatalogueValue;
use App\Models\FarmLand;
use App\Models\LogActivities;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return redirect('login');
});

Route::group(["prefix"=> ""], function () {
    Route::get("/login", [LoginController::class, 'showLoginForm'])->name('show_login_form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get("/logout", [LoginController::class, 'logout'])->name('logout');
});




Route::group(['middleware' => ['auth']], function () {
    Route::get("/dashboard", [HomeController::class, 'dashboard'])->name('dashboard');
    Route::resource('season-masters', SeasonMasterController::class)->names('season-masters');
    Route::resource('crop-informations', CropMasterController::class);
    Route::resource('catalogue-values', CatalogueValueController::class)->only('index');
    //Route::get("/season-master", [HomeController::class, 'season_master'])->name('season_master');

     // Farmer Details
    Route::get("/farmer", [FarmersController::class, 'index'])->name('farmer.index');
    Route::get("/farmer_location", [FarmersController::class, 'farmer_location'])->name('farmer_location.index');
    Route::get("/farmer/dtajax", [FarmersController::class, 'dtajax'])->name('farmer.dtajax');
    Route::get("/farmer/{id}", [FarmersController::class, 'show'])->name('farmer.show');
    Route::post("/import-csv", [FarmersController::class, 'importCSV'])->name('farmer.import_csv');

    //Country
    Route::get("/country", [CountryController::class, 'index'])->name('country.index');
    Route::get("/country/dtajax", [CountryController::class, 'dtajax'])->name('country.dtajax');
    Route::get("/country/create", [CountryController::class, 'create'])->name('country.create');
    Route::post("/add_country", [CountryController::class, 'store'])->name('country.store');

    // Staff
    Route::get("/staff", [StaffController::class, 'index'])->name('staff.index');
    Route::get("/staff/dtajax", [StaffController::class, 'dtajax'])->name('staff.dtajax');
    Route::get("/staff/create", [StaffController::class, 'create'])->name('staff.create');
    Route::post("/add_staff", [StaffController::class, 'store'])->name('staff.store');


    //Province
    Route::get("/province", [ProvinceController::class, 'index'])->name('province.index');
    Route::get("/province/dtajax", [ProvinceController::class, 'dtajax'])->name('province.dtajax');
    Route::get("/province/create", [ProvinceController::class, 'create'])->name('province.create');
    Route::post("/add_province", [ProvinceController::class, 'store'])->name('province.store');
    Route::get("/province_filter_by_country/{id}", [ProvinceController::class, 'filter_by_country'])->name('province.filter_by_country');

    //District
    Route::get("/district", [DistrictController::class, 'index'])->name('district.index');
    Route::get("/district/dtajax", [DistrictController::class, 'dtajax'])->name('district.dtajax');
    Route::get("/district/create", [DistrictController::class, 'create'])->name('district.create');
    Route::post("/add_district", [DistrictController::class, 'store'])->name('district.store');
    Route::get("/district_filter_by_province/{id}", [DistrictController::class, 'filter_by_province'])->name('district.filter_by_province');

    //Commune
    Route::get("/commune", [CommuneController::class, 'index'])->name('commune.index');
    Route::get("/commune/dtajax", [CommuneController::class, 'dtajax'])->name('commune.dtajax');
    Route::get("/commune/create", [CommuneController::class, 'create'])->name('commune.create');
    Route::post("/add_commune", [CommuneController::class, 'store'])->name('commune.store');
    Route::get("/commnue_filter_by_district/{id}", [CommuneController::class, 'filter_by_district'])->name('commnue.filter_by_district');
    
    //Log Activities
    Route::get("/staff_activities", [LogActivitiesController::class, 'index'])->name('log_activities.index');
    // Route::get("/commune/dtajax", [LogActivities::class, 'dtajax'])->name('commune.dtajax');
    // Route::get("/commune/create", [LogActivities::class, 'create'])->name('commune.create');
    // Route::post("/add_commune", [LogActivities::class, 'store'])->name('commune.store');
    // Route::get("/commnue_filter_by_district/{id}", [LogActivities::class, 'filter_by_district'])->name('commnue.filter_by_district');

    // Farm land
    Route::get("/farm_land", [FarmLandController::class, 'index'])->name('farm_land.index');


});


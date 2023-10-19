<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\Admin\HomeController;
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

        //Country
    Route::get("/country", [CountryController::class, 'index'])->name('country.index');
    Route::post("/add_country", [CountryController::class, 'store'])->name('country.store');

    //Province
    Route::get("/province", [ProvinceController::class, 'index'])->name('province.index');
    Route::post("/add_province", [ProvinceController::class, 'store'])->name('province.store');
    Route::get("/province_filter_by_country/{id}", [ProvinceController::class, 'filter_by_country'])->name('province.filter_by_country');

    //District
    Route::get("/district", [DistrictController::class, 'index'])->name('district.index');
    Route::post("/add_district", [DistrictController::class, 'store'])->name('district.store');
    Route::get("/district_filter_by_province/{id}", [DistrictController::class, 'filter_by_province'])->name('district.filter_by_province');

    //Commune
    Route::get("/commnue", [CommuneController::class, 'index'])->name('commnue.index');
    Route::post("/add_commune", [CommuneController::class, 'store'])->name('commnue.store');
    Route::get("/commnue_filter_by_district/{id}", [CommuneController::class, 'filter_by_district'])->name('commnue.filter_by_district');

    
});


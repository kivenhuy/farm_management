<?php

use App\Http\Controllers\Auth\LoginController;
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
});


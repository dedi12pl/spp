<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoordinateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['super_admin', 'auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/cor', [MapController::class, 'cor']);

    Route::get('/map', [MapController::class, 'index']);
    Route::get('/gmap', [MapController::class, 'googleMap']);
    Route::get('/list-tower', [MapController::class, 'map']);

    Route::resource('/coordinates', CoordinateController::class);

    Route::get('/maps', [MapController::class, 'index']);
});

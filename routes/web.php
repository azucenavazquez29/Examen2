<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\EstadisticasController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('home_estadisticas');

Route::resource('films', FilmController::class);
Route::resource('actors', ActorController::class);
Route::resource('customers', CustomerController::class);
Route::resource('categories', CategoryController::class);
Route::resource('languages', LanguageController::class);


Route::prefix('rent')->name('rent.')->group(function () {
    Route::post('/', [RentController::class, 'store'])->name('store');
    Route::put('/return/{rental}', [RentController::class, 'returnFilm'])->name('return');
    Route::get('/', [RentController::class, 'index'])->name('index');
    Route::get('/active', [RentController::class, 'active'])->name('active');
});
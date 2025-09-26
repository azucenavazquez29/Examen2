<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\RentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// CRUD de películas
Route::resource('films', FilmController::class);

// CRUD de actores
Route::resource('actors', ActorController::class);

// Rutas para rentas
Route::prefix('rent')->name('rent.')->group(function () {
    Route::post('/', [RentController::class, 'store'])->name('store');
    Route::put('/return/{rental}', [RentController::class, 'returnFilm'])->name('return');
    Route::get('/', [RentController::class, 'index'])->name('index');
    Route::get('/active', [RentController::class, 'active'])->name('active');
});
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/stats', [EstadisticasController::class, 'index'])->name('stats');
Route::get('/administrador', [AdministradorController::class, 'index'])->name('administrador');
Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente');

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

Route::get('films/import', [FilmController::class, 'importForm'])->name('films.import-form');
Route::post('films/import', [FilmController::class, 'importFromOmdb'])->name('films.import-omdb');
Route::post('films/search-omdb', [FilmController::class, 'searchOmdb'])->name('films.search-omdb');
Route::resource('films', FilmController::class);

// routes/web.php

Route::prefix('reportes')->name('reportes.')->group(function () {
    // Página principal de reportes
    Route::get('/', [ReportController::class, 'index'])->name('index');
    
    // Rentas por Sucursal
    Route::get('/rentas/sucursal', [ReportController::class, 'exportSalesByStoreCsv'])->name('rentas.sucursal.excel');
    Route::get('/rentas/sucursal/pdf', [ReportController::class, 'exportSalesByStorePdf'])->name('rentas.sucursal.pdf');
    
    // Rentas por Categoría
    Route::get('/rentas/categoria', [ReportController::class, 'exportSalesByCategoryCsv'])->name('rentas.categoria.excel');
    Route::get('/rentas/categoria/pdf', [ReportController::class, 'exportSalesByCategoryPdf'])->name('rentas.categoria.pdf');
    
    // Rentas por Actor
    Route::get('/rentas/actor', [ReportController::class, 'exportSalesByActorCsv'])->name('rentas.actor.excel');
    Route::get('/rentas/actor/pdf', [ReportController::class, 'exportSalesByActorPdf'])->name('rentas.actor.pdf');
    
    // Ingresos por Tienda
    Route::get('/ingresos/tienda', [ReportController::class, 'exportIncomeByStoreCsv'])->name('ingresos.tienda.excel');
    Route::get('/ingresos/tienda/pdf', [ReportController::class, 'exportIncomeByStorePdf'])->name('ingresos.tienda.pdf');
    
    // Top Películas
    Route::get('/peliculas/top', [ReportController::class, 'exportTopMoviesCsv'])->name('peliculas.top.excel');
    Route::get('/peliculas/top/pdf', [ReportController::class, 'exportTopMoviesPdf'])->name('peliculas.top.pdf');
    
    // Top Clientes
    Route::get('/clientes/top', [ReportController::class, 'exportTopCustomersCsv'])->name('clientes.top.excel');
    Route::get('/clientes/top/pdf', [ReportController::class, 'exportTopCustomersPdf'])->name('clientes.top.pdf');
});
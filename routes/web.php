<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ControllerEmpleado;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;

// ============================================
// RUTA PÚBLICA (sin autenticación)
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================
// RUTAS DE AUTENTICACIÓN (sin middleware)
// ============================================
Route::prefix('auth')->name('auth.')->group(function () {
    // Empleados
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Clientes
    Route::post('/cliente/login', [AuthController::class, 'loginCliente'])->name('cliente.login');
    Route::post('/cliente/logout', [AuthController::class, 'logoutCliente'])->name('cliente.logout');
    Route::post('/cliente/register', [AuthController::class, 'registerCliente'])->name('cliente.register');
    
    // Verificación de sesión
    Route::get('/check', [AuthController::class, 'check'])->name('check');
});

// ============================================
// RECUPERACIÓN DE CONTRASEÑA (sin middleware)
// ============================================
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// ============================================
// RUTAS PROTEGIDAS - EMPLEADO (cualquier empleado)
// ============================================
Route::middleware(['staff'])->group(function () {
    
    // Dashboard de empleado
    Route::get('/empleado', [ControllerEmpleado::class, 'index'])->name('empleado');
    
    // Gestión de rentas
    Route::prefix('rent')->name('rent.')->group(function () {
        Route::get('/', [RentController::class, 'index'])->name('index');
        Route::get('/active', [RentController::class, 'active'])->name('active');
        Route::post('/', [RentController::class, 'store'])->name('store');
        Route::put('/return/{rental}', [RentController::class, 'returnFilm'])->name('return');
    });
    
    // Gestión de clientes (empleado puede ver/crear clientes)
    Route::resource('customers', CustomerController::class);
});

// ============================================
// RUTAS PROTEGIDAS - SOLO ADMINISTRADOR
// ============================================
Route::middleware(['staff', 'admin'])->group(function () {
    
    // Estadísticas
    Route::get('/stats', [EstadisticasController::class, 'index'])->name('stats');
    
    // Panel de administrador
    Route::get('/administrador', [AdministradorController::class, 'index'])->name('administrador');
    
    // Gestión de actores
    Route::resource('actors', ActorController::class);
    
    // Gestión de categorías
    Route::resource('categories', CategoryController::class);
    
    // Gestión de idiomas
    Route::resource('languages', LanguageController::class);
    
    // Gestión de películas
    Route::get('films/import', [FilmController::class, 'importForm'])->name('films.import-form');
    Route::post('films/import', [FilmController::class, 'importFromOmdb'])->name('films.import-omdb');
    Route::post('films/search-omdb', [FilmController::class, 'searchOmdb'])->name('films.search-omdb');
    Route::resource('films', FilmController::class);
    
    // ============================================
    // REPORTES
    // ============================================
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/rentas/sucursal', [ReportController::class, 'exportSalesByStoreCsv'])->name('rentas.sucursal.excel');
        Route::get('/rentas/sucursal/pdf', [ReportController::class, 'exportSalesByStorePdf'])->name('rentas.sucursal.pdf');
        Route::get('/rentas/categoria', [ReportController::class, 'exportSalesByCategoryCsv'])->name('rentas.categoria.excel');
        Route::get('/rentas/categoria/pdf', [ReportController::class, 'exportSalesByCategoryPdf'])->name('rentas.categoria.pdf');
        Route::get('/rentas/actor', [ReportController::class, 'exportSalesByActorCsv'])->name('rentas.actor.excel');
        Route::get('/rentas/actor/pdf', [ReportController::class, 'exportSalesByActorPdf'])->name('rentas.actor.pdf');
        Route::get('/ingresos/tienda', [ReportController::class, 'exportIncomeByStoreCsv'])->name('ingresos.tienda.excel');
        Route::get('/ingresos/tienda/pdf', [ReportController::class, 'exportIncomeByStorePdf'])->name('ingresos.tienda.pdf');
        Route::get('/peliculas/top', [ReportController::class, 'exportTopMoviesCsv'])->name('peliculas.top.excel');
        Route::get('/peliculas/top/pdf', [ReportController::class, 'exportTopMoviesPdf'])->name('peliculas.top.pdf');
        Route::get('/clientes/top', [ReportController::class, 'exportTopCustomersCsv'])->name('clientes.top.excel');
        Route::get('/clientes/top/pdf', [ReportController::class, 'exportTopCustomersPdf'])->name('clientes.top.pdf');
    });
});

// ============================================
// RUTAS PROTEGIDAS - CLIENTE
// ============================================
Route::middleware(['customer'])->group(function () {
    
    // Dashboard principal del cliente
    Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente');
    
    // Catálogo de películas
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
    
    // Historial de rentas del cliente
    Route::get('/cliente/rentas', [ClienteController::class, 'rentals'])->name('cliente.rentals');
    
    // Pagos y cargos del cliente
    Route::get('/cliente/pagos', [ClienteController::class, 'payments'])->name('cliente.payments');
    
    // Perfil del cliente
    Route::get('/cliente/perfil', [ClienteController::class, 'profile'])->name('cliente.profile');
    Route::put('/cliente/perfil', [ClienteController::class, 'updateProfile'])->name('cliente.profile.update');
});
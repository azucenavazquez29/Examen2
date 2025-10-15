<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film_filter;
use App\Models\Category;
use App\Models\Actor;
use App\Models\Language;
use App\Models\Customer;

class FilmController_filter extends Controller
{
    public function index(Request $request)
    {
        $userRole = session('user_role');
        $storeId = session('store_id');

        // Obtener datos para los filtros
        $categories = Category::orderBy('name')->get();
        $actors = Actor::orderBy('first_name')->get();
        $languages = Language::orderBy('name')->get();
        
        // Obtener clientes según el rol
        if ($userRole === 'admin') {
            $customers = Customer::orderBy('first_name')->get();
        } else {
            $customers = Customer::where('store_id', $storeId)->orderBy('first_name')->get();
        }

        // Query base
        $query = Film_filter::query();

        // Filtro por título
        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('category.category_id', $request->category);
            });
        }

        // Filtro por actor
        if ($request->filled('actor')) {
            $query->whereHas('actors', function($q) use ($request) {
                $q->where('actor.actor_id', $request->actor);
            });
        }

        // Filtro por idioma
        if ($request->filled('language')) {
            $query->where('language_id', $request->language);
        }

        // Filtro por rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filtro por precio de renta
        if ($request->filled('rental_rate')) {
            $query->where('rental_rate', '<=', $request->rental_rate);
        }

        // Filtro por disponibilidad
        if ($request->filled('available')) {
            if ($request->available == '1') {
                // Solo películas con copias disponibles
                if ($userRole === 'admin') {
                    $query->whereHas('inventory', function($q) {
                        $q->whereDoesntHave('rentals', function($rental) {
                            $rental->whereNull('return_date');
                        });
                    });
                } else {
                    $query->whereHas('inventory', function($q) use ($storeId) {
                        $q->where('store_id', $storeId)
                          ->whereDoesntHave('rentals', function($rental) {
                              $rental->whereNull('return_date');
                          });
                    });
                }
            } else if ($request->available == '0') {
                // Solo películas sin copias disponibles
                if ($userRole === 'admin') {
                    $query->whereDoesntHave('inventory', function($q) {
                        $q->whereDoesntHave('rentals', function($rental) {
                            $rental->whereNull('return_date');
                        });
                    });
                } else {
                    $query->whereDoesntHave('inventory', function($q) use ($storeId) {
                        $q->where('store_id', $storeId)
                          ->whereDoesntHave('rentals', function($rental) {
                              $rental->whereNull('return_date');
                          });
                    });
                }
            }
        }

        // Obtener películas con paginación
        $films_tst = $query->orderBy('title')->paginate(12)->withQueryString();

        return view('empleado_normal', compact(
            'films_tst', 
            'categories', 
            'actors', 
            'languages', 
            'customers',
            'userRole',
            'storeId'
        ));
    }
}
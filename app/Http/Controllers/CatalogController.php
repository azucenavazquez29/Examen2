<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Category;
use App\Models\Language;
use App\Models\Actor;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $actors = Actor::limit(10)->get();
        $actors_tst =$actors->take(8);
        $query = Film::query()->with(['language', 'actors', 'categories', 'inventory']);
        
        // Búsqueda por título
        if ($request->has('search') && $request->search) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }
        
    // Filtro por categoría - CORREGIDO
    if ($request->has('category') && $request->category) {
        $query->whereHas('categories', function($q) use ($request) {
            $q->where('category.category_id', $request->category); // Especifica la tabla
        });
    }
        
    // Filtro por actor - CORREGIDO
    if ($request->has('actor') && $request->actor) {
        $query->whereHas('actors', function($q) use ($request) {
            $q->where('actor.actor_id', $request->actor); // Especifica la tabla
        });
    }
        
        // Filtro por idioma
        if ($request->has('language') && $request->language) {
            $query->where('language_id', $request->language);
        }
        
        // Filtro por disponibilidad
        if ($request->has('available') && $request->available == '1') {
            $query->whereHas('inventory', function($q) {
                $q->whereDoesntHave('rentals', function($rental) {
                    $rental->whereNull('return_date');
                });
            });
        }
        
        $films = $query->paginate(12);
        $categories = Category::all();
        $languages = Language::all();
        $actors = Actor::orderBy('first_name')->get();
        
        return view('catalog', compact('films', 'categories', 'languages', 'actors','actors_tst'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Actor;
use App\Models\Customer;
use App\Models\Staff;

class HomeController extends FilmController
{

    public function index()
    {
    
    //$films = Film::with(['actors', 'language', 'categories','rentals.customer'])->get();
    $films = Film::limit(36)->get();
    //$actors = Actor::all();
    $actors = Actor::limit(10)->get();

    //ELEMENTOS PARA LOGEO DE EL SISTMA
    //$staff = Staff::all();  
    $staff = Staff::limit(1)->get(); 
    //$customers = Customer::all();
    $customers = Customer::limit(30)->get();


    //ELEMENTOS QUE E MUESTRAN EN NLA PESTAÑA PRINCIPAL
    $actors_tst =$actors->take(8);
    //$films_tst = $films->take(8);
    $films_tst = Film::paginate(12);

   
    return view('index', compact('films', 'films_tst','customers','actors_tst','staff'));
    }

    public function usuarios() {
        //ELEMENTOS PARA LOGEO DE EL SISTMA
        $staff = Staff::all();  
        $customers = Customer::all();
        return view('index', compact('staff','customers'));
    }


    public function catalog(Request $request)
{
    $query = Film::query()->with(['language', 'actors', 'categories']);
    
    // Búsqueda por título
    if ($request->has('search') && $request->search) {
        $query->where('title', 'LIKE', '%' . $request->search . '%');
    }
    
    // Filtro por categoría
    if ($request->has('category') && $request->category) {
        $query->whereHas('categories', function($q) use ($request) {
            $q->where('category_id', $request->category);
        });
    }
    
    // Filtro por actor
    if ($request->has('actor') && $request->actor) {
        $query->whereHas('actors', function($q) use ($request) {
            $q->where('actor_id', $request->actor);
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
    
    return view('catalog', compact('films', 'categories', 'languages', 'actors'));
}

}

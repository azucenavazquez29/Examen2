<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Language;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;




class FilmController extends Controller
{


    public function index()
{
    // Traer todas las películas
    $films = Film::with(['actors', 'language', 'categories'])->get();

    // Pasar ambas variables a la vista
    return view('films.index', compact('films'));
}




    public function create()
    {
        $languages = Language::all();
        $categories = Category::all();

        return view('films.create', compact('languages', 'categories'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'release_year' => 'nullable|integer',
        'language_id' => 'required|integer',
        'original_language_id' => 'nullable|integer',
        'rental_duration' => 'required|integer',
        'rental_rate' => 'required|numeric',
        'length' => 'nullable|integer',
        'replacement_cost' => 'required|numeric',
        'rating' => 'nullable|string',
        'special_features' => 'nullable|array',
        'special_features.*' => 'string',
    ]);

    // Convertir el array de special_features en string compatible con SET
    if(isset($validated['special_features'])){
        $validated['special_features'] = implode(',', $validated['special_features']);
    }

    Film::create($validated);

    return redirect()->route('films.index')->with('success', 'Película creada.');
}


    public function show(Film $film)
    {
        // Incluye relaciones para mostrar más info
        $film->load(['actors', 'language', 'originalLanguage', 'categories']);
        return view('films.show', compact('film'));
    }

public function edit(Film $film)
{
    $languages = Language::all();
    $categories = Category::all();

    return view('films.edit', compact('film', 'languages', 'categories'));
}


public function update(Request $request, Film $film)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'release_year' => 'nullable|integer',
        'language_id' => 'required|integer',
        'original_language_id' => 'nullable|integer',
        'rental_duration' => 'required|integer',
        'rental_rate' => 'required|numeric',
        'length' => 'nullable|integer',
        'replacement_cost' => 'required|numeric',
        'rating' => 'nullable|string',
        'special_features' => 'nullable|array',
        'special_features.*' => 'string',
    ]);

    if(isset($validated['special_features'])){
        $validated['special_features'] = implode(',', $validated['special_features']);
    }

    $film->update($validated);

    return redirect()->route('films.index')->with('success', 'Película actualizada.');
}


    public function destroy(Film $film)
    {
        $film->delete();
        return redirect()->route('films.index')->with('success', 'Película eliminada.');
    }


public function rentals()
{
    return $this->hasMany(Rental::class, 'film_id', 'film_id');
}

public function currentRental()
{
    return $this->rentals()->whereNull('return_date')->first();
}


}

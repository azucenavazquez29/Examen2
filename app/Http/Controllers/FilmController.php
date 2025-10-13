<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Language;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Actor;
use App\Services\OmdbService;
use Illuminate\Http\Request;
use Exception;


class FilmController extends Controller
{

    protected $omdbService;

    public function index()
{
    
    $films = Film::with(['actors', 'language', 'categories'])->get();

    
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

    
    if(isset($validated['special_features'])){
        $validated['special_features'] = implode(',', $validated['special_features']);
    }

    Film::create($validated);

    return redirect()->route('films.index')->with('success', 'Película creada.');
}


public function importForm()
    {
        $languages = Language::all();
        $categories = Category::all();
        return view('films.import', compact('languages', 'categories'));
    }

     public function importFromOmdb(Request $request)
    {
        try {
            $request->validate([
                'search_query' => 'required|string|min:2|max:255',
                'language_id' => 'required|integer|exists:language,language_id',
                'rental_duration' => 'required|integer|min:1|max:10',
                'rental_rate' => 'required|numeric|min:0.99|max:99.99',
                'replacement_cost' => 'required|numeric|min:1|max:999.99',
                'categories' => 'required|array|min:1',
                'categories.*' => 'integer|exists:category,category_id',
            ]);

            // Instanciar el servicio
            $omdbService = new OmdbService();
            
            // Buscar en OMDb
            $movieData = $omdbService->searchByTitle($request->search_query);

            // Verificar si ya existe
            $existingFilm = Film::where('title', $movieData['title'])->first();
            
            if ($existingFilm) {
                return back()->with('error', 'La película "' . $movieData['title'] . '" ya existe en la base de datos.');
            }

            $film = Film::create([
                'title' => $movieData['title'],
                'description' => $movieData['description'],
                'release_year' => $movieData['release_year'],
                'language_id' => $request->language_id,
                'rental_duration' => $request->rental_duration,
                'rental_rate' => $request->rental_rate,
                'length' => $movieData['length'],
                'replacement_cost' => $request->replacement_cost,
                'rating' => $movieData['rating'],
                // No incluyas special_features aquí, déjalo null o vacío
            ]);

            // Asociar categorías
            if (!empty($request->categories)) {
                $film->categories()->attach($request->categories);
            }

            // Procesar y agregar actores
            if (!empty($movieData['actors'])) {
                foreach ($movieData['actors'] as $actorName) {
                    $actor = $this->getOrCreateActor($actorName);
                    $film->actors()->attach($actor->actor_id);
                }
            }

            return redirect()->route('films.index')->with('success', 
                'Película "' . $film->title . '" importada exitosamente desde OMDb con ' . 
                count($movieData['actors']) . ' actores.');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    public function show(Film $film)
    {
        
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


 private function getOrCreateActor($fullName)
    {
        $parts = explode(' ', trim($fullName), 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';

        return Actor::firstOrCreate(
            [
                'first_name' => $firstName,
                'last_name' => $lastName
            ],
            [
                'first_name' => $firstName,
                'last_name' => $lastName
            ]
        );
    }

  public function searchOmdb(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|min:2'
            ]);

            // LOG: Ver qué recibimos
            \Log::info('searchOmdb - Query recibido: ' . $request->input('query'));
            \Log::info('searchOmdb - Request all: ', $request->all());

            // Instanciar el servicio
            $omdbService = new OmdbService();
            
            $movieData = $omdbService->searchByTitle($request->input('query'));

            // Verificar duplicado
            $exists = Film::where('title', $movieData['title'])->exists();

            return response()->json([
                'success' => true,
                'data' => $movieData,
                'exists' => $exists
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    

}

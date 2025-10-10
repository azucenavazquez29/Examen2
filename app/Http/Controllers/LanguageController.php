<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Film;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
     public function index()
    {
        $languages = Language::with('films')->get();
        return view('languages.index', compact('languages'));
    }

    public function create()
    {
        $films = Film::all(); 
        return view('languages.create', compact('films'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            //'films' => 'nullable|array',
            //'films.*' => 'integer'
        ]);

        $language = Language::create($request->only('name'));

        //if ($request->has('films')) {
         //   $language->films()->sync($request->films);
        //}

        return redirect()->route('languages.index')->with('success', 'Idioma creado creada.');
    }

    public function edit(Language $language)
    {
        $films = Film::all();
        $selectedFilms = $language->films->pluck('film_id')->toArray();
        return view('languages.edit', compact('language', 'films', 'selectedFilms'));
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            //'films' => 'nullable|array',
            //'films.*' => 'integer'
        ]);

        $language->update($request->only('name'));

        //$language->films()->sync($request->films ?? []);

        return redirect()->route('languages.index')->with('success', 'Idioma actualizado.');
    }

    public function destroy(Language $language)
    {
        //$language->films()->detach(); 
        $language->delete();
        return redirect()->route('languages.index')->with('success', 'Idioma eliminado.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Film;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    public function index()
    {
        $actors = Actor::with('films')->get();
        return view('actors.index', compact('actors'));
    }

    public function create()
    {
        $films = Film::all(); 
        return view('actors.create', compact('films'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'films' => 'nullable|array',
            'films.*' => 'integer'
        ]);

        $actor = Actor::create($request->only('first_name', 'last_name'));

        if ($request->has('films')) {
            $actor->films()->sync($request->films);
        }

        return redirect()->route('actors.index')->with('success', 'Actor creado.');
    }

    public function edit(Actor $actor)
    {
        $films = Film::all();
        $selectedFilms = $actor->films->pluck('film_id')->toArray();
        return view('actors.edit', compact('actor', 'films', 'selectedFilms'));
    }

    public function update(Request $request, Actor $actor)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'films' => 'nullable|array',
            'films.*' => 'integer'
        ]);

        $actor->update($request->only('first_name', 'last_name'));

        $actor->films()->sync($request->films ?? []);

        return redirect()->route('actors.index')->with('success', 'Actor actualizado.');
    }

    public function destroy(Actor $actor)
    {
        $actor->films()->detach(); 
        $actor->delete();
        return redirect()->route('actors.index')->with('success', 'Actor eliminado.');
    }


    public function show(Actor $actor)
{
    $actor->load('films');
    return view('actors.show', compact('actor'));
}

public function films()
{
    return $this->belongsToMany(Film::class, 'film_actor', 'actor_id', 'film_id');
}
}

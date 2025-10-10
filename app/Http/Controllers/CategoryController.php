<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Film;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     public function index()
    {
        $categories = Category::with('films')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $films = Film::all(); 
        return view('categories.create', compact('films'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'films' => 'nullable|array',
            'films.*' => 'integer'
        ]);

        $category = Category::create($request->only('name'));

        if ($request->has('films')) {
            $category->films()->sync($request->films);
        }

        return redirect()->route('categories.index')->with('success', 'Categoria creada.');
    }

    public function edit(Category $category)
    {
        $films = Film::all();
        $selectedFilms = $category->films->pluck('film_id')->toArray();
        return view('categories.edit', compact('category', 'films', 'selectedFilms'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'films' => 'nullable|array',
            'films.*' => 'integer'
        ]);

        $category->update($request->only('name'));

        $category->films()->sync($request->films ?? []);

        return redirect()->route('categories.index')->with('success', 'Categoria actualizado.');
    }

    public function destroy(Category $category)
    {
        $category->films()->detach(); 
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categorias eliminada.');
    }
}

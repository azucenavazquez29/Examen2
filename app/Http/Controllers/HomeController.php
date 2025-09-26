<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Actor;
use App\Models\Customer;

class HomeController extends FilmController
{

        public function index()
{
    // Traer todas las películas
    $films = Film::with(['actors', 'language', 'categories','rentals.customer'])->get();
    $customers = Customer::all();
    $actors = Actor::all();

    $actors_tst =$actors->take(8);
    // Traer máximo 8 películas para destacadas
    $films_tst = $films->take(8);

    // Pasar ambas variables a la vista
    return view('index', compact('films', 'films_tst','customers','actors_tst'));
}

}

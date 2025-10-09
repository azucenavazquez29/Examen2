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
    $customers = Customer::limit(10)->get();


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

}

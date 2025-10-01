<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Rental;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;


class RentController extends Controller
{
  
    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:film,film_id',
            'customer_id' => 'required|exists:customer,customer_id',
        ]);

        $film = Film::findOrFail($request->film_id);
        
        
        if (!$film->isAvailable()) {
            return redirect()->back()->with('error', 'La película no está disponible para rentar.');
        }

        
        $inventory = Inventory::where('film_id', $film->film_id)
            ->whereDoesntHave('rentals', function($query) {
                $query->whereNull('return_date');
            })
            ->first();

        if (!$inventory) {
            return redirect()->back()->with('error', 'No hay copias disponibles de esta película.');
        }

        
        $staff = Staff::first();
        if (!$staff) {
            return redirect()->back()->with('error', 'Error del sistema: No hay personal disponible.');
        }

   
        Rental::create([
            'rental_date' => Carbon::now(),
            'inventory_id' => $inventory->inventory_id,
            'customer_id' => $request->customer_id,
            'staff_id' => $staff->staff_id,
            'return_date' => null,
        ]);

        $customer = Customer::find($request->customer_id);
        
        return redirect()->back()->with('success', 
            "Película '{$film->title}' rentada exitosamente a {$customer->first_name} {$customer->last_name}.");
    }

 
    public function returnFilm(Request $request, $rental_id)
    {
        $rental = Rental::with(['customer', 'inventory.film'])->findOrFail($rental_id);

      
        if ($rental->return_date) {
            return redirect()->back()->with('error', 'Esta película ya fue devuelta.');
        }

     
        $rental->update([
            'return_date' => Carbon::now()
        ]);

        $film = $rental->inventory->film;
        $customer = $rental->customer;

        return redirect()->back()->with('success', 
            "Película '{$film->title}' devuelta exitosamente por {$customer->first_name} {$customer->last_name}.");
    }

    
    public function index()
    {
        $rentals = Rental::with(['customer', 'inventory.film', 'staff'])
            ->orderBy('rental_date', 'desc')
            ->paginate(20);

        return view('rentals.index', compact('rentals'));
    }

    
    public function active()
    {
        $activeRentals = Rental::with(['customer', 'inventory.film', 'staff'])
            ->whereNull('return_date')
            ->orderBy('rental_date', 'desc')
            ->get();

        return view('rentals.active', compact('activeRentals'));
    }
}
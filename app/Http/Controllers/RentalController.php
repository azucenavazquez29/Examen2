<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Inventory;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    // Reservar película
    public function store(Request $request)
    {
        $filmId = $request->film_id;
        $customerId = $request->customer_id;

        // Buscar inventario disponible
        $inventory = Inventory::where('film_id', $filmId)
            ->whereDoesntHave('rentals', function ($q) {
                $q->whereNull('return_date');
            })
            ->first();

        if (!$inventory) {
            return back()->with('error', 'No hay copias disponibles.');
        }

        // Crear la renta
        Rental::create([
            'rental_date' => now(),
            'inventory_id' => $inventory->inventory_id,
            'customer_id' => $customerId,
            'staff_id' => 1, // fijo o según tu login
            'last_update' => now(),
        ]);

        return back()->with('success', 'Película reservada con éxito.');
    }

public function return(Rental $rental)
{
    $rental->update([
        'return_date' => now()
    ]);

    return back()->with('success', 'Película devuelta correctamente');
}


    
}

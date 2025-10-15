<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RentalController_avanzado extends Controller
{
    public function store(Request $request)
    {
        $userRole = Session::get('user_role');
        $userStoreId = Session::get('store_id');
        $staffId = Session::get('staff_id');

        // Determinar sucursal
        if ($userRole === 'admin') {
            $customer = DB::table('customer')->where('customer_id', $request->customer_id)->first();
            $storeToUse = $customer->store_id;
        } else {
            $storeToUse = $userStoreId;
        }

        // ✅ Buscar inventario EN LA SUCURSAL CORRECTA
        $inventoryId = DB::table('inventory')
            ->where('film_id', $request->film_id)
            ->where('store_id', $storeToUse)
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('rental')
                    ->whereColumn('rental.inventory_id', 'inventory.inventory_id')
                    ->whereNull('rental.return_date');
            })
            ->value('inventory_id');

        if (!$inventoryId) {
            return back()->with('error', '❌ No hay copias disponibles en tu sucursal');
        }

        // Crear la renta
        DB::table('rental')->insert([
            'rental_date' => now(),
            'inventory_id' => $inventoryId,
            'customer_id' => $request->customer_id,
            'staff_id' => $staffId,
            'return_date' => null,
            'last_update' => now()
        ]);

        return back()->with('success', '✅ Renta registrada en Sucursal #' . $storeToUse);
    }

    public function returnFilm($rentalId)
    {
        $rental = DB::table('rental')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->where('rental.rental_id', $rentalId)
            ->select('rental.*', 'inventory.store_id')
            ->first();

        $userRole = Session::get('user_role');
        $userStoreId = Session::get('store_id');

        if ($userRole !== 'admin' && $rental->store_id != $userStoreId) {
            return back()->with('error', '❌ No puedes devolver de otra sucursal');
        }

        DB::table('rental')
            ->where('rental_id', $rentalId)
            ->update(['return_date' => now(), 'last_update' => now()]);

        return back()->with('success', '✅ Película devuelta');
    }
}
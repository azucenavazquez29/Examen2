<?php

namespace App\Repositories;

use App\Repositories\Interfaces\InventarioRepositoryInterface;
use Illuminate\Support\Facades\DB;

class InventarioRepository implements InventarioRepositoryInterface
{
    public function obtenerPorTienda($tiendaId)
    {
        return DB::table('inventory')
            ->join('film', 'inventory.film_id', '=', 'film.film_id')
            ->join('store', 'inventory.store_id', '=', 'store.store_id')
            ->leftJoin('rental', function($join) {
                $join->on('inventory.inventory_id', '=', 'rental.inventory_id')
                     ->whereNull('rental.return_date');
            })
            ->where('inventory.store_id', $tiendaId)
            ->select(
                'inventory.inventory_id',
                'film.film_id',
                'film.title',
                'film.rental_rate',
                'store.store_id',
                DB::raw('CASE WHEN rental.rental_id IS NULL THEN "Disponible" ELSE "Rentada" END as estado')
            )
            ->get();
    }

    public function verificarDisponibilidad($inventarioId)
    {
        $rental = DB::table('rental')
            ->where('inventory_id', $inventarioId)
            ->whereNull('return_date')
            ->first();

        return $rental === null;
    }

    public function obtenerResumen($tiendaId)
    {
        $inventario = $this->obtenerPorTienda($tiendaId);
        
        return [
            'total' => $inventario->count(),
            'disponibles' => $inventario->where('estado', 'Disponible')->count(),
            'rentadas' => $inventario->where('estado', 'Rentada')->count(),
            'tienda_id' => $tiendaId
        ];
    }
}
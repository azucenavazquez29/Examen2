<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RankingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RankingRepository implements RankingRepositoryInterface
{
    public function obtenerRanking($tipo, $limite = 10)
    {
        // Este método será usado por las estrategias
        // pero la lógica específica estará en cada Strategy
        return [];
    }

    public function obtenerPeliculasPopulares($limite)
    {
        return DB::table('rental')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->join('film', 'inventory.film_id', '=', 'film.film_id')
            ->select(
                'film.film_id',
                'film.title',
                'film.rental_rate',
                'film.rating',
                DB::raw('COUNT(rental.rental_id) as total_rentas')
            )
            ->groupBy('film.film_id', 'film.title', 'film.rental_rate', 'film.rating')
            ->orderBy('total_rentas', 'DESC')
            ->limit($limite)
            ->get();
    }

    public function obtenerMejoresClientes($limite)
    {
        return DB::table('payment')
            ->join('customer', 'payment.customer_id', '=', 'customer.customer_id')
            ->select(
                'customer.customer_id',
                DB::raw('CONCAT(customer.first_name, " ", customer.last_name) as nombre_completo'),
                'customer.email',
                DB::raw('COUNT(payment.payment_id) as total_pagos'),
                DB::raw('SUM(payment.amount) as total_gastado')
            )
            ->groupBy('customer.customer_id', 'customer.first_name', 'customer.last_name', 'customer.email')
            ->orderBy('total_gastado', 'DESC')
            ->limit($limite)
            ->get();
    }

    public function obtenerCategoriasPopulares()
    {
        return DB::table('rental')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->join('film', 'inventory.film_id', '=', 'film.film_id')
            ->join('film_category', 'film.film_id', '=', 'film_category.film_id')
            ->join('category', 'film_category.category_id', '=', 'category.category_id')
            ->select(
                'category.category_id',
                'category.name as nombre_categoria',
                DB::raw('COUNT(rental.rental_id) as total_rentas')
            )
            ->groupBy('category.category_id', 'category.name')
            ->orderBy('total_rentas', 'DESC')
            ->get();
    }
}
<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PeliculaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PeliculaRepository implements PeliculaRepositoryInterface
{
    public function obtenerTodas($porPagina, $pagina, $filtros = [])
    {
        $query = DB::table('film')
            ->select(
                'film_id',
                'title',
                'description',
                'release_year',
                'rental_rate',
                'rental_duration',
                'length',
                'rating',
                'special_features'
            );

        // Aplicar filtros
        if (isset($filtros['busqueda']) && !empty($filtros['busqueda'])) {
            $busqueda = $filtros['busqueda'];
            $query->where(function($q) use ($busqueda) {
                $q->where('title', 'LIKE', "%{$busqueda}%")
                  ->orWhere('description', 'LIKE', "%{$busqueda}%");
            });
        }

        if (isset($filtros['rating'])) {
            $query->where('rating', $filtros['rating']);
        }

        return $query->offset(($pagina - 1) * $porPagina)
                     ->limit($porPagina)
                     ->get();
    }

    public function obtenerPorId($id)
    {
        return DB::table('film')
            ->leftJoin('language', 'film.language_id', '=', 'language.language_id')
            ->select('film.*', 'language.name as idioma')
            ->where('film.film_id', $id)
            ->first();
    }

    public function obtenerPorCategoria($categoriaId)
    {
        return DB::table('film')
            ->join('film_category', 'film.film_id', '=', 'film_category.film_id')
            ->join('category', 'film_category.category_id', '=', 'category.category_id')
            ->where('category.category_id', $categoriaId)
            ->select(
                'film.film_id',
                'film.title',
                'film.description',
                'film.rental_rate',
                'film.rating',
                'category.name as categoria'
            )
            ->get();
    }

    public function contarTotal($filtros = [])
    {
        $query = DB::table('film');

        if (isset($filtros['busqueda']) && !empty($filtros['busqueda'])) {
            $busqueda = $filtros['busqueda'];
            $query->where(function($q) use ($busqueda) {
                $q->where('title', 'LIKE', "%{$busqueda}%")
                  ->orWhere('description', 'LIKE', "%{$busqueda}%");
            });
        }

        if (isset($filtros['rating'])) {
            $query->where('rating', $filtros['rating']);
        }

        return $query->count();
    }

    public function obtenerCategoriasDepelicula($peliculaId)
    {
        return DB::table('film_category')
            ->join('category', 'film_category.category_id', '=', 'category.category_id')
            ->where('film_category.film_id', $peliculaId)
            ->pluck('category.name');
    }

    public function obtenerActoresDePelicula($peliculaId)
    {
        return DB::table('film_actor')
            ->join('actor', 'film_actor.actor_id', '=', 'actor.actor_id')
            ->where('film_actor.film_id', $peliculaId)
            ->select('actor.actor_id', 'actor.first_name', 'actor.last_name')
            ->get();
    }
}
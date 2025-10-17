<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeliculasApiController extends Controller
{
    /**
     * Obtener listado de todas las películas
     * GET /api/peliculas
     */
    public function obtenerPeliculas(Request $request)
    {
        try {
            $porPagina = $request->input('por_pagina', 15);
            $pagina = $request->input('pagina', 1);
            $busqueda = $request->input('busqueda', '');

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

            // Si hay búsqueda, filtrar
            if (!empty($busqueda)) {
                $query->where('title', 'LIKE', "%{$busqueda}%")
                      ->orWhere('description', 'LIKE', "%{$busqueda}%");
            }

            $total = $query->count();
            $peliculas = $query->offset(($pagina - 1) * $porPagina)
                              ->limit($porPagina)
                              ->get();

            return response()->json([
                'exito' => true,
                'mensaje' => 'Películas obtenidas correctamente',
                'datos' => $peliculas,
                'paginacion' => [
                    'total' => $total,
                    'pagina_actual' => (int) $pagina,
                    'por_pagina' => (int) $porPagina,
                    'total_paginas' => ceil($total / $porPagina)
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener películas: ' . $e->getMessage());
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener las películas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener detalles de una película específica
     * GET /api/peliculas/{id}
     */
    public function obtenerPeliculaPorId($id)
    {
        try {
            $pelicula = DB::table('film')
                ->leftJoin('language', 'film.language_id', '=', 'language.language_id')
                ->select(
                    'film.*',
                    'language.name as idioma'
                )
                ->where('film.film_id', $id)
                ->first();

            if (!$pelicula) {
                return response()->json([
                    'exito' => false,
                    'mensaje' => 'Película no encontrada'
                ], 404);
            }

            // Obtener categorías de la película
            $categorias = DB::table('film_category')
                ->join('category', 'film_category.category_id', '=', 'category.category_id')
                ->where('film_category.film_id', $id)
                ->pluck('category.name');

            // Obtener actores de la película
            $actores = DB::table('film_actor')
                ->join('actor', 'film_actor.actor_id', '=', 'actor.actor_id')
                ->where('film_actor.film_id', $id)
                ->select('actor.actor_id', 'actor.first_name', 'actor.last_name')
                ->get();

            $pelicula->categorias = $categorias;
            $pelicula->actores = $actores;

            return response()->json([
                'exito' => true,
                'mensaje' => 'Película obtenida correctamente',
                'datos' => $pelicula
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener película: ' . $e->getMessage());
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener la película',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener inventario disponible por tienda
     * GET /api/inventario
     */
    public function obtenerInventario(Request $request)
    {
        try {
            $tiendaId = $request->input('tienda_id', 1);

            $inventario = DB::table('inventory')
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

            $disponibles = $inventario->where('estado', 'Disponible')->count();
            $rentadas = $inventario->where('estado', 'Rentada')->count();

            return response()->json([
                'exito' => true,
                'mensaje' => 'Inventario obtenido correctamente',
                'datos' => $inventario,
                'resumen' => [
                    'total' => $inventario->count(),
                    'disponibles' => $disponibles,
                    'rentadas' => $rentadas,
                    'tienda_id' => $tiendaId
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener inventario: ' . $e->getMessage());
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener el inventario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener ranking de películas más rentadas
     * GET /api/rankings/peliculas-populares
     */
    public function obtenerPeliculasPopulares(Request $request)
    {
        try {
            $limite = $request->input('limite', 10);

            $ranking = DB::table('rental')
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

            return response()->json([
                'exito' => true,
                'mensaje' => 'Ranking de películas populares obtenido correctamente',
                'datos' => $ranking,
                'total_resultados' => $ranking->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener ranking: ' . $e->getMessage());
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener el ranking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener ranking de clientes por ingresos
     * GET /api/rankings/mejores-clientes
     */
    public function obtenerMejoresClientes(Request $request)
    {
        try {
            $limite = $request->input('limite', 10);

            $ranking = DB::table('payment')
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

            return response()->json([
                'exito' => true,
                'mensaje' => 'Ranking de mejores clientes obtenido correctamente',
                'datos' => $ranking,
                'total_resultados' => $ranking->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener ranking de clientes: ' . $e->getMessage());
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener el ranking de clientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener categorías más populares
     * GET /api/rankings/categorias-populares
     */
    public function obtenerCategoriasPopulares()
    {
        try {
            $ranking = DB::table('rental')
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

            return response()->json([
                'exito' => true,
                'mensaje' => 'Ranking de categorías populares obtenido correctamente',
                'datos' => $ranking,
                'total_resultados' => $ranking->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener categorías populares: ' . $e->getMessage());
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener las categorías populares',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar películas por categoría
     * GET /api/peliculas/categoria/{categoria_id}
     */
    public function obtenerPeliculasPorCategoria($categoriaId)
    {
        try {
            $peliculas = DB::table('film')
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

            if ($peliculas->isEmpty()) {
                return response()->json([
                    'exito' => false,
                    'mensaje' => 'No se encontraron películas para esta categoría'
                ], 404);
            }

            return response()->json([
                'exito' => true,
                'mensaje' => 'Películas obtenidas correctamente',
                'datos' => $peliculas,
                'total_resultados' => $peliculas->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener películas por categoría: ' . $e->getMessage());
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener las películas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
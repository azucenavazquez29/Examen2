<?php

namespace App\Services;

use App\Repositories\Interfaces\PeliculaRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PeliculaService
{
    protected $peliculaRepository;

    public function __construct(PeliculaRepositoryInterface $peliculaRepository)
    {
        $this->peliculaRepository = $peliculaRepository;
    }

    public function listarPeliculas($porPagina, $pagina, $filtros = [])
    {
        try {
            $peliculas = $this->peliculaRepository->obtenerTodas($porPagina, $pagina, $filtros);
            $total = $this->peliculaRepository->contarTotal($filtros);

            return [
                'exito' => true,
                'mensaje' => 'Películas obtenidas correctamente',
                'datos' => $peliculas,
                'paginacion' => [
                    'total' => $total,
                    'pagina_actual' => (int) $pagina,
                    'por_pagina' => (int) $porPagina,
                    'total_paginas' => ceil($total / $porPagina)
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error en PeliculaService::listarPeliculas: ' . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerDetallePelicula($id)
    {
        try {
            $pelicula = $this->peliculaRepository->obtenerPorId($id);

            if (!$pelicula) {
                return [
                    'exito' => false,
                    'mensaje' => 'Película no encontrada'
                ];
            }

            // Enriquecer con categorías y actores
            $pelicula->categorias = $this->peliculaRepository->obtenerCategoriasDepelicula($id);
            $pelicula->actores = $this->peliculaRepository->obtenerActoresDePelicula($id);

            return [
                'exito' => true,
                'mensaje' => 'Película obtenida correctamente',
                'datos' => $pelicula
            ];

        } catch (\Exception $e) {
            Log::error('Error en PeliculaService::obtenerDetallePelicula: ' . $e->getMessage());
            throw $e;
        }
    }

    public function listarPorCategoria($categoriaId)
    {
        try {
            $peliculas = $this->peliculaRepository->obtenerPorCategoria($categoriaId);

            if ($peliculas->isEmpty()) {
                return [
                    'exito' => false,
                    'mensaje' => 'No se encontraron películas para esta categoría'
                ];
            }

            return [
                'exito' => true,
                'mensaje' => 'Películas obtenidas correctamente',
                'datos' => $peliculas,
                'total_resultados' => $peliculas->count()
            ];

        } catch (\Exception $e) {
            Log::error('Error en PeliculaService::listarPorCategoria: ' . $e->getMessage());
            throw $e;
        }
    }
}
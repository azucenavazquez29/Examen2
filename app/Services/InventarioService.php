<?php

namespace App\Services;

use App\Repositories\Interfaces\InventarioRepositoryInterface;
use Illuminate\Support\Facades\Log;

class InventarioService
{
    protected $inventarioRepository;

    public function __construct(InventarioRepositoryInterface $inventarioRepository)
    {
        $this->inventarioRepository = $inventarioRepository;
    }

    public function obtenerInventarioPorTienda($tiendaId)
    {
        try {
            $inventario = $this->inventarioRepository->obtenerPorTienda($tiendaId);
            $resumen = $this->inventarioRepository->obtenerResumen($tiendaId);

            return [
                'exito' => true,
                'mensaje' => 'Inventario obtenido correctamente',
                'datos' => $inventario,
                'resumen' => $resumen
            ];

        } catch (\Exception $e) {
            Log::error('Error en InventarioService::obtenerInventarioPorTienda: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verificarDisponibilidad($inventarioId)
    {
        try {
            $disponible = $this->inventarioRepository->verificarDisponibilidad($inventarioId);

            return [
                'exito' => true,
                'disponible' => $disponible,
                'mensaje' => $disponible ? 'Artículo disponible' : 'Artículo no disponible'
            ];

        } catch (\Exception $e) {
            Log::error('Error en InventarioService::verificarDisponibilidad: ' . $e->getMessage());
            throw $e;
        }
    }
}
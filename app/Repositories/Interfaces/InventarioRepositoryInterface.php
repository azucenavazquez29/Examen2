<?php

namespace App\Repositories\Interfaces;

interface InventarioRepositoryInterface
{
    public function obtenerPorTienda($tiendaId);
    public function verificarDisponibilidad($inventarioId);
    public function obtenerResumen($tiendaId);
}
<?php

namespace App\Repositories\Interfaces;

interface PeliculaRepositoryInterface
{
    public function obtenerTodas($porPagina, $pagina, $filtros = []);
    public function obtenerPorId($id);
    public function obtenerPorCategoria($categoriaId);
    public function contarTotal($filtros = []);
    public function obtenerCategoriasDepelicula($peliculaId);
    public function obtenerActoresDePelicula($peliculaId);
}
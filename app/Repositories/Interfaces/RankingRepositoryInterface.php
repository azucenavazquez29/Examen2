<?php

namespace App\Repositories\Interfaces;

interface RankingRepositoryInterface
{
    public function obtenerRanking($tipo, $limite = 10);
}
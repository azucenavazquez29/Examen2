<?php

namespace App\Strategies\Ranking;

use App\Repositories\RankingRepository;

class MejoresClientesStrategy implements RankingStrategyInterface
{
    protected $repository;

    public function __construct(RankingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function obtenerRanking($limite = 10)
    {
        return $this->repository->obtenerMejoresClientes($limite);
    }
}
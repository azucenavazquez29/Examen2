<?php

namespace App\Strategies\Ranking;

interface RankingStrategyInterface
{
    public function obtenerRanking($limite = 10);
}
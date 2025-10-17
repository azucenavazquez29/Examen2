<?php

namespace App\Services;

use App\Strategies\Ranking\RankingStrategyInterface;
use Illuminate\Support\Facades\Log;

class RankingService
{
    protected $strategy;

    public function setStrategy(RankingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function obtenerRanking($limite = 10)
    {
        try {
            if (!$this->strategy) {
                throw new \Exception('No se ha definido una estrategia de ranking');
            }

            $ranking = $this->strategy->obtenerRanking($limite);

            return [
                'exito' => true,
                'mensaje' => 'Ranking obtenido correctamente',
                'datos' => $ranking,
                'total_resultados' => $ranking->count()
            ];

        } catch (\Exception $e) {
            Log::error('Error en RankingService::obtenerRanking: ' . $e->getMessage());
            throw $e;
        }
    }
}
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\PeliculaRepositoryInterface;
use App\Repositories\Interfaces\InventarioRepositoryInterface;
use App\Repositories\Interfaces\RankingRepositoryInterface;
use App\Repositories\PeliculaRepository;
use App\Repositories\InventarioRepository;
use App\Repositories\RankingRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Binding de interfaces a implementaciones
        $this->app->bind(
            PeliculaRepositoryInterface::class,
            PeliculaRepository::class
        );

        $this->app->bind(
            InventarioRepositoryInterface::class,
            InventarioRepository::class
        );

        $this->app->bind(
            RankingRepositoryInterface::class,
            RankingRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
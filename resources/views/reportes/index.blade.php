@extends('layouts.app')

@section('title', 'Panel de Reportes')

@section('content')
<div class="container py-4">
    <h1 class="text-center my-4 display-5 fw-bold" style="color:white !important; font-weight:bolder !important;">
            <i class="fas fa-chart-line me-2"></i>Panel de Reportes y Estadísticas
    </h1>

    <div class="row g-4">


        <!-- Rentas por Sucursal -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm p-3">
                <h5 class="card-title">🏬 Rentas por Sucursal</h5>
                <p class="text-muted">Ver estadísticas de rentas por tienda</p>
                <a href="{{ route('reportes.rentas.sucursal.excel') }}" class="btn btn-success btn-sm mb-1">Excel</a>
                <a href="{{ route('reportes.rentas.sucursal.pdf') }}" class="btn btn-danger btn-sm mb-1">PDF</a>
            </div>
        </div>

        <!-- Rentas por Categoría -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm p-3">
                <h5 class="card-title">🎞️ Rentas por Categoría</h5>
                <p class="text-muted">Desglose por tipo de película</p>
                <a href="{{ route('reportes.rentas.categoria.excel') }}" class="btn btn-success btn-sm mb-1">Excel</a>
                <a href="{{ route('reportes.rentas.categoria.pdf') }}" class="btn btn-danger btn-sm mb-1">PDF</a>
            </div>
        </div>

        <!-- Rentas por Actor -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm p-3">
                <h5 class="card-title">🎭 Rentas por Actor</h5>
                <p class="text-muted">Ranking de actores más vistos</p>
                <a href="{{ route('reportes.rentas.actor.excel') }}" class="btn btn-success btn-sm mb-1">Excel</a>
                <a href="{{ route('reportes.rentas.actor.pdf') }}" class="btn btn-danger btn-sm mb-1">PDF</a>
            </div>
        </div>

        <!-- Ingresos por Tienda -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm p-3">
                <h5 class="card-title">💰 Ingresos por Tienda</h5>
                <p class="text-muted">Reportes financieros por sucursal</p>
                <a href="{{ route('reportes.ingresos.tienda.excel') }}" class="btn btn-success btn-sm mb-1">Excel</a>
                <a href="{{ route('reportes.ingresos.tienda.pdf') }}" class="btn btn-danger btn-sm mb-1">PDF</a>
            </div>
        </div>

        <!-- Películas más Rentadas -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm p-3">
                <h5 class="card-title">🔥 Películas más Rentadas</h5>
                <p class="text-muted">Ranking general de películas</p>
                <a href="{{ route('reportes.peliculas.top.excel') }}" class="btn btn-success btn-sm mb-1">Excel</a>
                <a href="{{ route('reportes.peliculas.top.pdf') }}" class="btn btn-danger btn-sm mb-1">PDF</a>
            </div>
        </div>

        <!-- Clientes con más Rentas -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm p-3">
                <h5 class="card-title">👤 Clientes con más Rentas</h5>
                <p class="text-muted">Top de clientes frecuentes</p>
                <a href="{{ route('reportes.clientes.top.excel') }}" class="btn btn-success btn-sm mb-1">Excel</a>
                <a href="{{ route('reportes.clientes.top.pdf') }}" class="btn btn-danger btn-sm mb-1">PDF</a>
            </div>
        </div>

    </div>
</div>
@endsection

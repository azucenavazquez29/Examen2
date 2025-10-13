@extends('layouts.app')

@section('title', 'Detalle de Tienda')

@section('content')
<div class="container mt-4" style="color:white !important;">
    <h2>Detalle de Tienda #{{ $store->store_id }}</h2>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <h5>Gerente</h5>
            <p>{{ $store->manager?->first_name }} {{ $store->manager?->last_name }}</p>

            <h5>Dirección</h5>
            <p>{{ $store->address?->address }}<br>
            {{ $store->address?->district }} (Ciudad ID: {{ $store->address?->city_id }})</p>

            <h5>Teléfono</h5>
            <p>{{ $store->address?->phone }}</p>
        </div>

        <div class="col-md-6">
            <h5>Estadísticas</h5>
            <ul class="list-group">
                <li class="list-group-item">Empleados activos: {{ $stats['active_staff'] }}</li>
                <li class="list-group-item">Clientes activos: {{ $stats['active_customers'] }}</li>
                <li class="list-group-item">Películas en inventario: {{ $stats['total_films'] }}</li>
                <li class="list-group-item">Rentas activas: {{ $stats['active_rentals'] }}</li>
                <li class="list-group-item">Rentas vencidas: {{ $stats['overdue_rentals'] }}</li>
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('stores.edit', $store->store_id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('stores.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

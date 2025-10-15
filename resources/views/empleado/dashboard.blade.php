@extends('layouts.app')

@section('title', 'Panel de Empleado')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 mb-3 text-dark">Bienvenido, {{ $staff->first_name }} {{ $staff->last_name }}</h1>
                    <p class="text-muted mb-2">
                        <span class="fw-semibold">Sucursal:</span> {{ $staff->city }}, {{ $staff->country }}
                    </p>
                    <p class="text-muted small">
                        Último acceso: {{ \Carbon\Carbon::parse($staff->last_update)->format('d/m/Y H:i:s') }}
                    </p>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <a href="{{ route('empleado.mis-accesos') }}" class="btn btn-outline-primary btn-sm" title="Ver mi historial de accesos">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                            </svg>
                            Mis Accesos
                        </a>
                        <a href="{{ route('empleado.historial-accesos') }}" class="btn btn-outline-secondary btn-sm" title="Ver historial de todos los empleados">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                            </svg>
                            Historial General
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-4 mb-4">
        <!-- Total Clientes -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Total Clientes</p>
                            <h3 class="card-title mb-0">{{ $stats['total_clientes'] }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rentas Activas -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Rentas Activas</p>
                            <h3 class="card-title mb-0">{{ $stats['rentas_activas'] }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Películas Disponibles -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Inventario Total</p>
                            <h3 class="card-title mb-0">{{ $stats['peliculas_disponibles'] }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos del Mes -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Ingresos Este Mes</p>
                            <h3 class="card-title mb-0">${{ number_format($stats['ingresos_mes'], 2) }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menú de Acciones Rápidas -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('empleado.clientes.index') }}" class="card text-decoration-none shadow-sm h-100 transition" style="transition: all 0.3s;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <svg class="text-primary" width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title mb-1">Gestión de Clientes</h5>
                            <p class="card-text small text-muted mb-0">Ver, registrar y actualizar clientes</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('empleado.inventario.index') }}" class="card text-decoration-none shadow-sm h-100 transition" style="transition: all 0.3s;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <svg class="text-warning" width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title mb-1">Inventario</h5>
                            <p class="card-text small text-muted mb-0">Consultar películas y copias</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="#" class="card text-decoration-none shadow-sm h-100 transition" style="transition: all 0.3s;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <svg class="text-success" width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                            </svg>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title mb-1">Gestión de Rentas</h5>
                            <p class="card-text small text-muted mb-0">Registrar y devolver películas</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Rentas Recientes -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Rentas Recientes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold">ID</th>
                            <th class="text-uppercase text-muted small fw-bold">Cliente</th>
                            <th class="text-uppercase text-muted small fw-bold">Película</th>
                            <th class="text-uppercase text-muted small fw-bold">Fecha Renta</th>
                            <th class="text-uppercase text-muted small fw-bold">Estado</th>
                            <th class="text-uppercase text-muted small fw-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentasRecientes as $renta)
                        <tr>
                            <td class="small">{{ $renta->rental_id }}</td>
                            <td class="small">
                                {{ $renta->first_name }} {{ $renta->last_name }}
                            </td>
                            <td class="small">{{ $renta->title }}</td>
                            <td class="small text-muted">
                                {{ \Carbon\Carbon::parse($renta->rental_date)->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @if($renta->return_date)
                                    <span class="badge bg-success">Devuelto</span>
                                @else
                                    <span class="badge bg-warning text-dark">En renta</span>
                                @endif
                            </td>
                            <td class="small">
                                <a href="#" class="link-primary text-decoration-none">Ver detalles</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay rentas recientes
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .transition:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection
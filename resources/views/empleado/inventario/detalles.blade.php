@extends('layouts.app')

@section('title', 'Detalles de Película')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="color:white !important;" class="h3 fw-bold text-dark">{{ $pelicula->title }}</h1>
            <p style="color:white !important;" class="text-muted mt-2">Detalles e historial de movimientos</p>
        </div>
        <a href="{{ route('empleado.inventario.index') }}" class="btn btn-secondary">
            Volver al Inventario
        </a>
    </div>

    <!-- Información de la Película -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">ID Película</p>
                    <p class="fs-5 fw-bold text-dark mb-0">{{ $pelicula->film_id }}</p>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">Categoría</p>
                    <p class="fs-5 fw-bold text-dark mb-0">{{ $pelicula->category ?? 'Sin categoría' }}</p>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">Clasificación</p>
                    <p class="fs-5 fw-bold mb-0">
                        <span class="badge
                            @if($pelicula->rating == 'G') bg-success
                            @elseif($pelicula->rating == 'PG') bg-info
                            @elseif($pelicula->rating == 'PG-13') bg-warning text-dark
                            @elseif($pelicula->rating == 'R') bg-danger
                            @else bg-dark
                            @endif">
                            {{ $pelicula->rating }}
                        </span>
                    </p>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">Idioma</p>
                    <p class="fs-5 fw-bold text-dark mb-0">{{ $pelicula->language }}</p>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">Duración</p>
                    <p class="fs-5 fw-bold text-dark mb-0">{{ $pelicula->length }} min</p>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">Año de Lanzamiento</p>
                    <p class="fs-5 fw-bold text-dark mb-0">{{ $pelicula->release_year }}</p>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">Tarifa de Renta</p>
                    <p class="fs-5 fw-bold text-success mb-0">${{ number_format($pelicula->rental_rate, 2) }}</p>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small fw-medium mb-1">Días de Renta</p>
                    <p class="fs-5 fw-bold text-dark mb-0">{{ $pelicula->rental_duration }} días</p>
                </div>
            </div>

            @if($pelicula->description)
            <div class="mt-4">
                <p class="text-muted small fw-medium mb-2">Descripción</p>
                <p class="text-dark">{{ $pelicula->description }}</p>
            </div>
            @endif

            @if($pelicula->special_features)
            <div class="mt-3">
                <p class="text-muted small fw-medium mb-2">Características Especiales</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(explode(',', $pelicula->special_features) as $feature)
                        <span class="badge bg-secondary bg-opacity-25 text-dark">
                            {{ $feature }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas de Copias -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Total de Copias</p>
                            <h3 class="card-title mb-0">{{ $copias->count() }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Copias Disponibles</p>
                            <h3 class="card-title mb-0">{{ $copias->whereNull('rental_id')->count() }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Copias Rentadas</p>
                            <h3 class="card-title mb-0">{{ $copias->whereNotNull('rental_id')->count() }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado de las Copias -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Estado de las Copias en Sucursal</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted small fw-bold">ID Inventario</th>
                        <th class="text-uppercase text-muted small fw-bold">Estado</th>
                        <th class="text-uppercase text-muted small fw-bold">Cliente Actual</th>
                        <th class="text-uppercase text-muted small fw-bold">Fecha de Renta</th>
                        <th class="text-uppercase text-muted small fw-bold">Días Transcurridos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($copias as $copia)
                    <tr>
                        <td class="small fw-semibold text-dark">
                            #{{ $copia->inventory_id }}
                        </td>
                        <td>
                            @if($copia->rental_id)
                                <span class="badge bg-warning text-dark">En Renta</span>
                            @else
                                <span class="badge bg-success">Disponible</span>
                            @endif
                        </td>
                        <td class="small text-dark">
                            @if($copia->rental_id)
                                {{ $copia->first_name }} {{ $copia->last_name }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="small text-dark">
                            @if($copia->rental_date)
                                {{ \Carbon\Carbon::parse($copia->rental_date)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="small text-dark">
                            @if($copia->rental_date)
                                @php
                                    $dias = \Carbon\Carbon::parse($copia->rental_date)->diffInDays(now());
                                @endphp
                                {{ $dias }} día(s)
                                @if($dias > $pelicula->rental_duration)
                                    <span class="text-danger fw-semibold ms-2">
                                        (Atrasado +{{ $dias - $pelicula->rental_duration }} días)
                                    </span>
                                @endif
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            No hay copias de esta película en la sucursal
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Historial de Movimientos -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Historial de Movimientos (Últimas 50 rentas)</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted small fw-bold">ID Renta</th>
                        <th class="text-uppercase text-muted small fw-bold">ID Inventario</th>
                        <th class="text-uppercase text-muted small fw-bold">Cliente</th>
                        <th class="text-uppercase text-muted small fw-bold">Fecha Renta</th>
                        <th class="text-uppercase text-muted small fw-bold">Fecha Devolución</th>
                        <th class="text-uppercase text-muted small fw-bold">Duración</th>
                        <th class="text-uppercase text-muted small fw-bold">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $movimiento)
                    <tr>
                        <td class="small fw-semibold text-dark">
                            #{{ $movimiento->rental_id }}
                        </td>
                        <td class="small text-dark">
                            {{ $movimiento->inventory_id }}
                        </td>
                        <td class="small text-dark">
                            {{ $movimiento->first_name }} {{ $movimiento->last_name }}
                        </td>
                        <td class="small text-dark">
                            {{ \Carbon\Carbon::parse($movimiento->rental_date)->format('d/m/Y H:i') }}
                        </td>
                        <td class="small text-dark">
                            @if($movimiento->return_date)
                                {{ \Carbon\Carbon::parse($movimiento->return_date)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-warning fw-semibold">Pendiente</span>
                            @endif
                        </td>
                        <td class="small text-dark">
                            @if($movimiento->return_date)
                                @php
                                    $dias = \Carbon\Carbon::parse($movimiento->rental_date)->diffInDays(\Carbon\Carbon::parse($movimiento->return_date));
                                @endphp
                                {{ $dias }} día(s)
                            @else
                                @php
                                    $dias = \Carbon\Carbon::parse($movimiento->rental_date)->diffInDays(now());
                                @endphp
                                {{ $dias }} día(s)
                            @endif
                        </td>
                        <td>
                            @if($movimiento->return_date)
                                @php
                                    $diasRenta = \Carbon\Carbon::parse($movimiento->rental_date)->diffInDays(\Carbon\Carbon::parse($movimiento->return_date));
                                @endphp
                                @if($diasRenta > $pelicula->rental_duration)
                                    <span class="badge bg-danger bg-opacity-75">
                                        Devuelto con retraso
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        Devuelto a tiempo
                                    </span>
                                @endif
                            @else
                                @php
                                    $diasActual = \Carbon\Carbon::parse($movimiento->rental_date)->diffInDays(now());
                                @endphp
                                @if($diasActual > $pelicula->rental_duration)
                                    <span class="badge bg-danger">
                                        Atrasado
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        En renta
                                    </span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="text-center text-muted py-5">
                                <svg class="d-block mx-auto mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h6 class="fw-semibold text-dark">Sin historial</h6>
                                <p class="small">Esta película no tiene movimientos registrados</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
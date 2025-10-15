@extends('layouts.app')

@section('title', 'Inventario de Películas')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="color:white !important;" class="h3 fw-bold text-dark">Inventario de Películas</h1>
            <p style="color:white !important;" class="text-muted mt-2">Consulta y administra el inventario de tu sucursal</p>
        </div>
        <a href="{{ route('empleado.dashboard') }}" class="btn btn-secondary">
            Volver al Dashboard
        </a>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('empleado.inventario.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label fw-medium">
                            Buscar Película
                        </label>
                        <input 
                            type="text" 
                            name="search" 
                            id="search" 
                            value="{{ $search }}" 
                            placeholder="Buscar por título..." 
                            class="form-control"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="category" class="form-label fw-medium">
                            Categoría
                        </label>
                        <select 
                            name="category" 
                            id="category" 
                            class="form-select"
                        >
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->name }}" {{ $category == $cat->name ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="actor" class="form-label fw-medium">
                            Actor
                        </label>
                        <select 
                            name="actor" 
                            id="actor" 
                            class="form-select"
                        >
                            <option value="">Todos los actores</option>
                            @foreach($actores as $act)
                                <option value="{{ $act->actor_id }}" {{ $actor == $act->actor_id ? 'selected' : '' }}>
                                    {{ $act->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="language" class="form-label fw-medium">
                            Idioma
                        </label>
                        <select 
                            name="language" 
                            id="language" 
                            class="form-select"
                        >
                            <option value="">Todos los idiomas</option>
                            @foreach($idiomas as $idioma)
                                <option value="{{ $idioma->language_id }}" {{ $language == $idioma->language_id ? 'selected' : '' }}>
                                    {{ $idioma->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Buscar
                    </button>
                    @if($search || $category || $actor || $language)
                    <a href="{{ route('empleado.inventario.index') }}" class="btn btn-secondary">
                        Limpiar Filtros
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Inventario -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted small fw-bold">ID</th>
                        <th class="text-uppercase text-muted small fw-bold">Título</th>
                        <th class="text-uppercase text-muted small fw-bold">Categoría</th>
                        <th class="text-uppercase text-muted small fw-bold">Idioma</th>
                        <th class="text-uppercase text-muted small fw-bold">Clasificación</th>
                        <th class="text-uppercase text-muted small fw-bold">Tarifa</th>
                        <th class="text-uppercase text-muted small fw-bold text-center">Total Copias</th>
                        <th class="text-uppercase text-muted small fw-bold text-center">Disponibles</th>
                        <th class="text-uppercase text-muted small fw-bold text-center">Rentadas</th>
                        <th class="text-uppercase text-muted small fw-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peliculas as $pelicula)
                    <tr>
                        <td class="small fw-semibold text-dark">
                            {{ $pelicula->film_id }}
                        </td>
                        <td class="small text-dark">
                            <div class="fw-medium">{{ $pelicula->title }}</div>
                        </td>
                        <td class="small">
                            <span class="badge bg-primary">
                                {{ $pelicula->category ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td class="small">
                            <span class="badge bg-info">
                                {{ $pelicula->language_name }}
                            </span>
                        </td>
                        <td class="small">
                            <span class="badge
                                @if($pelicula->rating == 'G') bg-success
                                @elseif($pelicula->rating == 'PG') bg-info
                                @elseif($pelicula->rating == 'PG-13') bg-warning text-dark
                                @elseif($pelicula->rating == 'R') bg-danger
                                @else bg-dark
                                @endif">
                                {{ $pelicula->rating }}
                            </span>
                        </td>
                        <td class="small text-dark fw-semibold">
                            ${{ number_format($pelicula->rental_rate, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="fs-6 fw-bold text-dark">{{ $pelicula->total_copias }}</span>
                        </td>
                        <td class="text-center">
                            <span class="fs-6 fw-bold {{ $pelicula->copias_disponibles > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $pelicula->copias_disponibles }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fs-6 fw-bold text-warning">{{ $pelicula->copias_rentadas }}</span>
                        </td>
                        <td class="small">
                            <a href="{{ route('empleado.inventario.detalles', $pelicula->film_id) }}" 
                               class="link-primary text-decoration-none d-flex align-items-center gap-1">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                                Ver Detalles
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="text-center text-muted py-5">
                                <svg class="d-block mx-auto mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                </svg>
                                <h6 class="fw-semibold text-dark">No se encontraron películas</h6>
                                <p class="small">
                                    {{ $search || $category || $actor || $language ? 'Intenta con otros términos de búsqueda' : 'No hay inventario disponible' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($peliculas->hasPages())
        <div class="border-top">
            <div class="card-body">
                {{ $peliculas->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
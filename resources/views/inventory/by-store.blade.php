@extends('layouts.app')

@section('title', 'Inventario de Tienda #' . $store->store_id)

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado con información de la tienda -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-store me-2"></i>Tienda #{{ $store->store_id }}
                    </h2>
                    <p class="mb-1">
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                        <strong>{{ $store->address->address }}</strong>
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-city me-2 text-muted"></i>
                        {{ $store->address->city->city }}, {{ $store->address->city->country->country }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        CP: {{ $store->address->postal_code ?? 'N/A' }}
                        <span class="ms-3">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            {{ $store->address->phone }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        @php
            $totalCopies = $inventoryByFilm->sum('total_copies');
            $totalAvailable = $inventoryByFilm->sum('available_copies');
            $totalRented = $totalCopies - $totalAvailable;
            $totalFilms = $inventoryByFilm->count();
            $availabilityPercent = $totalCopies > 0 ? round(($totalAvailable / $totalCopies) * 100) : 0;
        @endphp
        
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Películas Diferentes</h6>
                            <h2 class="mb-0">{{ $totalFilms }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-film fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total de Copias</h6>
                            <h2 class="mb-0">{{ $totalCopies }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-boxes fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Copias Disponibles</h6>
                            <h2 class="mb-0">{{ $totalAvailable }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Copias Rentadas</h6>
                            <h2 class="mb-0">{{ $totalRented }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de Disponibilidad General -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h6 class="mb-2">Disponibilidad General de la Tienda</h6>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar bg-{{ $availabilityPercent >= 50 ? 'success' : ($availabilityPercent >= 25 ? 'warning' : 'danger') }}" 
                     role="progressbar" 
                     style="width: {{ $availabilityPercent }}%">
                    <strong>{{ $availabilityPercent }}% Disponible</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventario por Película -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-film me-2"></i>Inventario de Películas
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Película</th>
                            <th>Año</th>
                            <th>Clasificación</th>
                            <th class="text-center">Total Copias</th>
                            <th class="text-center">Disponibles</th>
                            <th class="text-center">Rentadas</th>
                            <th class="text-center">% Disponibilidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryByFilm as $filmInventory)
                            @php
                                $rentedCopies = $filmInventory->total_copies - $filmInventory->available_copies;
                                $availability = $filmInventory->total_copies > 0 
                                    ? round(($filmInventory->available_copies / $filmInventory->total_copies) * 100) 
                                    : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $filmInventory->film->title }}</strong>
                                    @if($filmInventory->film->description)
                                        <br>
                                        <small class="text-muted">
                                            {{ Str::limit($filmInventory->film->description, 80) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $filmInventory->film->release_year }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $filmInventory->film->rating }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary fs-6">
                                        {{ $filmInventory->total_copies }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6">
                                        {{ $filmInventory->available_copies }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark fs-6">
                                        {{ $rentedCopies }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height: 25px; min-width: 100px;">
                                        <div class="progress-bar bg-{{ $availability >= 50 ? 'success' : ($availability > 0 ? 'warning' : 'danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $availability }}%">
                                            {{ $availability }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('inventory.by-film', $filmInventory->film_id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No hay inventario en esta tienda</h5>
                                    <p class="text-muted mb-0">
                                        Agregue copias desde el <a href="{{ route('inventory.index') }}">listado principal de inventario</a>
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Películas con Stock Bajo -->
    @if($inventoryByFilm->where('available_copies', '>', 0)->where('available_copies', '<', 3)->count() > 0)
        <div class="card mt-4 border-warning shadow">
            <div class="card-header bg-warning">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Películas con Stock Bajo (Menos de 3 copias disponibles)
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($inventoryByFilm->where('available_copies', '>', 0)->where('available_copies', '<', 3) as $lowStock)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $lowStock->film->title }}</h6>
                                    <small class="text-muted">
                                        {{ $lowStock->available_copies }} 
                                        {{ $lowStock->available_copies == 1 ? 'copia disponible' : 'copias disponibles' }}
                                    </small>
                                </div>
                                <div>
                                    <button type="button" 
                                            class="btn btn-sm btn-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addCopiesModal"
                                            data-film-id="{{ $lowStock->film_id }}"
                                            data-film-title="{{ $lowStock->film->title }}">
                                        <i class="fas fa-plus me-1"></i>Agregar Copias
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal: Agregar Copias -->
<div class="modal fade" id="addCopiesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('inventory.bulk-add') }}" method="POST">
                @csrf
                <input type="hidden" name="store_id" value="{{ $store->store_id }}">
                <input type="hidden" name="film_id" id="modal_film_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        Agregar Copias a Tienda #{{ $store->store_id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Agregando copias para: <strong id="modal_film_title"></strong>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad de Copias *</label>
                        <input type="number" 
                               class="form-control" 
                               id="quantity" 
                               name="quantity" 
                               min="1" 
                               max="50" 
                               value="5" 
                               required>
                        <small class="text-muted">Máximo: 50 copias por operación</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Agregar Copias
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addCopiesModal = document.getElementById('addCopiesModal');
    
    if (addCopiesModal) {
        addCopiesModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const filmId = button.getAttribute('data-film-id');
            const filmTitle = button.getAttribute('data-film-title');
            
            document.getElementById('modal_film_id').value = filmId;
            document.getElementById('modal_film_title').textContent = filmTitle;
        });
    }
});
</script>
@endpush
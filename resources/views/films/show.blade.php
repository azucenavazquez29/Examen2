@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Columna izquierda: Información básica -->
        <div class="col-md-4">
            <div class="card shadow-lg" style="border-radius: 0.5rem;">
                <div class="card-body text-center">
                    <!-- Imagen -->
                    <img width="100%" src="images/imagen_alargada2.jpg" alt="{{ $film->title }}" 
                         class="rounded mb-3" style="object-fit: cover; height: 400px;">
                    
                    <!-- Información básica -->
                    <h5 class="card-title fw-bold">{{ $film->title }}</h5>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary mb-2">{{ $film->rating }}</span>
                        @if($film->release_year)
                            <span class="badge bg-secondary mb-2">{{ $film->release_year }}</span>
                        @endif
                    </div>

                    <!-- Pricing -->
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">Precio Renta</small>
                                <h5 class="mb-0">${{ $film->rental_rate }}</h5>
                            </div>
                            <div>
                                <small class="text-muted">Reemplazo</small>
                                <h5 class="mb-0">${{ $film->replacement_cost }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Información de inventario -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <h6 class="text-primary fw-bold">{{ $film->availableCopies() }}</h6>
                                <small>Disponibles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <h6 class="text-secondary fw-bold">{{ $film->totalCopies() }}</h6>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de disponibilidad -->
                    @if($film->isAvailable())
                        <div class="alert alert-success">
                            <strong>✅ Disponible</strong><br>
                            {{ $film->availableCopies() }} de {{ $film->totalCopies() }} copias
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <strong>🚫 No disponible</strong><br>
                            {{ $film->activeRentals()->count() }} rentas activas
                        </div>
                    @endif

                    <!-- Botones de acción para admin -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('films.edit', $film->film_id) }}" class="btn btn-warning mb-2">
                            <i class="fas fa-edit"></i> Editar Película
                        </a>
                        
                        <button class="btn btn-info mb-2" data-bs-toggle="modal" data-bs-target="#rentalsModal">
                            <i class="fas fa-list"></i> Ver Rentas ({{ $film->activeRentals()->count() }})
                        </button>

                        <form action="{{ route('films.destroy', $film->film_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 mb-2" 
                                    onclick="return confirm('¿Eliminar esta película? Esta acción no se puede deshacer.')">
                                <i class="fas fa-trash"></i> Eliminar Película
                            </button>
                        </form>

                        <a href="{{ route('films.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Detalles completos -->
        <div class="col-md-8">
            <!-- Sinopsis -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Sinopsis</h5>
                </div>
                <div class="card-body">
                    <p class="card-text" style="line-height: 1.8;">
                        {{ $film->description ?? 'No hay sinopsis disponible' }}
                    </p>
                </div>
            </div>

            <!-- Información técnica -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-cog"></i> Información Técnica</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">ID Película</small>
                                <p class="fw-bold">{{ $film->film_id }}</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Duración</small>
                                <p class="fw-bold">{{ $film->length ?? 'N/A' }} minutos</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Año de lanzamiento</small>
                                <p class="fw-bold">{{ $film->release_year ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Idioma</small>
                                <p class="fw-bold">{{ $film->language->name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Calificación</small>
                                <p class="fw-bold">
                                    <span class="badge bg-warning">{{ $film->rating }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-dollar-sign"></i> Información de Renta</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Precio por renta</small>
                                <p class="fw-bold text-primary" style="font-size: 1.5rem;">${{ $film->rental_rate }}</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Duración permitida</small>
                                <p class="fw-bold">{{ $film->rental_duration }} días</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Costo de reemplazo</small>
                                <p class="fw-bold text-danger">${{ $film->replacement_cost }}</p>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Características especiales</small>
                                <p class="fw-bold">{{ $film->special_features ?? 'Ninguna' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorías -->
            @if($film->categories->count() > 0)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-tags"></i> Categorías ({{ $film->categories->count() }})</h6>
                    </div>
                    <div class="card-body">
                        @foreach($film->categories as $category)
                            <span class="badge bg-info me-2 mb-2" style="font-size: 0.9rem;">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actores -->
            @if($film->actors->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Actores ({{ $film->actors->count() }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($film->actors as $actor)
                                <div class="col-md-6 mb-2">
                                    <div class="p-2 bg-light rounded">
                                        <i class="fas fa-user-circle"></i> 
                                        <strong>{{ $actor->first_name }} {{ $actor->last_name }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL: Rentas activas -->
    @if($film->activeRentals()->count() > 0)
        <div class="modal fade" id="rentalsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Rentas Activas: {{ $film->title }} ({{ $film->activeRentals()->count() }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Email</th>
                                        <th>Fecha Renta</th>
                                        <th>Días Transcurridos</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($film->activeRentals() as $rental)
                                        <tr>
                                            <td>
                                                <strong>{{ $rental->customer->first_name }} {{ $rental->customer->last_name }}</strong>
                                            </td>
                                            <td>{{ $rental->customer->email ?? 'N/A' }}</td>
                                            <td>{{ $rental->rental_date->format('d/m/Y H:i') }}</td>
                                            <td>{{ $rental->rental_date->diffInDays(now()) }} días</td>
                                            <td>
                                                @if($rental->isOverdue())
                                                    <span class="badge bg-danger">VENCIDO</span>
                                                @else
                                                    <span class="badge bg-info">ACTIVA</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('rent.return', $rental->rental_id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        Devolver
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="modal fade" id="rentalsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Rentas Activas: {{ $film->title }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Sin rentas activas</strong><br>
                            Todas las copias están disponibles en el inventario.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
</style>

@endsection
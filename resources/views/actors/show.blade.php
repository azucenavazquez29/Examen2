@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Columna izquierda: Información básica -->
        <div class="col-md-4">
            <div class="card shadow-lg" style="border-radius: 0.5rem;">
                <div class="card-body text-center">
                    <!-- Imagen (placeholder) -->
                    <div class="mb-3">
                        <div class="bg-light rounded p-5" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-circle" style="font-size: 100px; color: #ccc;"></i>
                        </div>
                    </div>
                    
                    <!-- Información básica -->
                    <h4 class="card-title fw-bold">{{ $actor->first_name }} {{ $actor->last_name }}</h4>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary mb-2">Actor/Actriz</span>
                    </div>

                    <!-- Estadísticas -->
                    <div class="row text-center mb-3">
                        <div class="col-12">
                            <div class="bg-light p-3 rounded">
                                <h6 class="text-primary fw-bold">{{ $actor->films->count() }}</h6>
                                <small>Películas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Información de registro -->
                    <div class="alert alert-info mb-3">
                        <small>
                            <strong>ID Actor:</strong> {{ $actor->actor_id }}<br>
                            <strong>Registrado:</strong> {{ $actor->last_update->format('d/m/Y') }}
                        </small>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('actors.edit', $actor->actor_id) }}" class="btn btn-warning mb-2">
                            <i class="fas fa-edit"></i> Editar Actor
                        </a>
                        
                        <form action="{{ route('actors.destroy', $actor->actor_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 mb-2" 
                                    onclick="return confirm('¿Eliminar este actor? Esta acción no se puede deshacer.')">
                                <i class="fas fa-trash"></i> Eliminar Actor
                            </button>
                        </form>

                        <a href="{{ route('actors.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Detalles y películas -->
        <div class="col-md-8">
            <!-- Información personal -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted">Nombre</small>
                                <p class="fw-bold">{{ $actor->first_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted">Apellido</small>
                                <p class="fw-bold">{{ $actor->last_name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Nombre Completo</small>
                        <p class="fw-bold" style="font-size: 1.3rem;">{{ $actor->first_name }} {{ $actor->last_name }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">ID del Actor</small>
                        <p class="fw-bold">{{ $actor->actor_id }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Última Actualización</small>
                        <p class="fw-bold">{{ $actor->last_update->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Películas en las que ha actuado -->
            @if($actor->films->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-film"></i> Filmografía ({{ $actor->films->count() }} películas)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Película</th>
                                        <th>Año</th>
                                        <th>Rating</th>
                                        <th>Duración</th>
                                        <th>Precio Renta</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actor->films as $film)
                                        <tr>
                                            <td>
                                                <strong>{{ $film->title }}</strong>
                                            </td>
                                            <td>{{ $film->release_year ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-warning">{{ $film->rating }}</span>
                                            </td>
                                            <td>{{ $film->length ?? 'N/A' }} min</td>
                                            <td>${{ $film->rental_rate }}</td>
                                            <td>
                                                <a href="{{ route('films.show', $film->film_id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Estadísticas de películas -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-primary fw-bold">{{ $actor->films->count() }}</h6>
                                        <small>Total de películas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-secondary fw-bold">
                                            {{ $actor->films->whereNotNull('release_year')->pluck('release_year')->max() ?? 'N/A' }}
                                        </h6>
                                        <small>Año más reciente</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-success fw-bold">
                                            ${{ number_format($actor->films->sum('rental_rate'), 2) }}
                                        </h6>
                                        <small>Total rentales</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-film"></i> Filmografía</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Sin películas registradas</strong><br>
                            Este actor no tiene películas asociadas en el sistema.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
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
    
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

@endsection
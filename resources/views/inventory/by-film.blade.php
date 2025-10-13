@extends('layouts.app')

@section('title', 'Inventario de ' . $film->title)

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado con información de la película -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-film me-2"></i>{{ $film->title }}
                    </h2>
                    <p class="text-muted mb-2">
                        <span class="badge bg-secondary me-2">{{ $film->release_year }}</span>
                        <span class="badge bg-info me-2">{{ $film->rating }}</span>
                        <span class="badge bg-dark me-2">{{ $film->length }} min</span>
                        <span class="badge bg-success">${{ number_format($film->rental_rate, 2) }}</span>
                    </p>
                    @if($film->description)
                        <p class="mb-0"><small>{{ Str::limit($film->description, 200) }}</small></p>
                    @endif
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary mb-2">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Inventario
                    </a>
                    <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addCopiesModal">
                        <i class="fas fa-plus-circle me-1"></i>Agregar Copias
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen por Tienda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-store me-2"></i>Inventario por Tienda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($inventoryByStore as $storeInventory)
                            @php
                                $rentedCopies = $storeInventory->total_copies - $storeInventory->available_copies;
                                $availability = $storeInventory->total_copies > 0 
                                    ? round(($storeInventory->available_copies / $storeInventory->total_copies) * 100) 
                                    : 0;
                            @endphp
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-{{ $availability >= 50 ? 'success' : ($availability > 0 ? 'warning' : 'danger') }}">
                                    <div class="card-header bg-{{ $availability >= 50 ? 'success' : ($availability > 0 ? 'warning' : 'danger') }} text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-store me-2"></i>
                                            Tienda #{{ $storeInventory->store->store_id }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                            <strong>{{ $storeInventory->store->address->city->city }}</strong>
                                        </p>
                                        <p class="mb-2">
                                            <small class="text-muted">
                                                {{ $storeInventory->store->address->address }}<br>
                                                {{ $storeInventory->store->address->city->country->country }}
                                            </small>
                                        </p>
                                        <hr>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <h4 class="text-secondary mb-0">{{ $storeInventory->total_copies }}</h4>
                                                <small class="text-muted">Total</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-success mb-0">{{ $storeInventory->available_copies }}</h4>
                                                <small class="text-muted">Disponibles</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-danger mb-0">{{ $rentedCopies }}</h4>
                                                <small class="text-muted">Rentadas</small>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-{{ $availability >= 50 ? 'success' : ($availability > 0 ? 'warning' : 'danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $availability }}%">
                                                    {{ $availability }}% disponible
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('inventory.by-store', $storeInventory->store->store_id) }}" 
                                           class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-eye me-1"></i>Ver todas las películas de esta tienda
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                    <h5>No hay copias de esta película en ninguna tienda</h5>
                                    <p class="mb-0">Agregue copias usando el botón "Agregar Copias"</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado Detallado de Todas las Copias -->
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Listado Detallado de Copias ({{ $allInventory->count() }} copias totales)
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Inventario</th>
                            <th>Tienda</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allInventory as $item)
                            <tr>
                                <td>
                                    <code class="fs-6">#{{ $item->inventory_id }}</code>
                                </td>
                                <td>
                                    <strong>Tienda #{{ $item->store->store_id }}</strong>
                                </td>
                                <td>
                                    {{ $item->store->address->city->city }}, 
                                    {{ $item->store->address->city->country->country }}
                                </td>
                                <td>
                                    @if($item->isAvailable())
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Disponible
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Rentada
                                        </span>
                                        @if($item->currentRental())
                                            <br>
                                            <small class="text-muted">
                                                Cliente: {{ $item->currentRental()->customer->first_name }} 
                                                {{ $item->currentRental()->customer->last_name }}
                                            </small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $item->last_update->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('inventory.edit', $item->inventory_id) }}" 
                                           class="btn btn-sm btn-warning"
                                           {{ !$item->isAvailable() ? 'disabled' : '' }}>
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('inventory.destroy', $item->inventory_id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de eliminar esta copia del inventario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    {{ !$item->isAvailable() ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No hay copias registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Agregar Copias -->
<div class="modal fade" id="addCopiesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('inventory.bulk-add') }}" method="POST">
                @csrf
                <input type="hidden" name="film_id" value="{{ $film->film_id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Agregar Copias de "{{ $film->title }}"
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Agregue múltiples copias de esta película a una tienda específica
                    </div>
                    
                    <div class="mb-3">
                        <label for="store_id" class="form-label">Tienda *</label>
                        <select class="form-select" id="store_id" name="store_id" required>
                            <option value="">Seleccione una tienda</option>
                            @foreach(\App\Models\Store::with('address.city.country')->get() as $store)
                                <option value="{{ $store->store_id }}">
                                    Tienda #{{ $store->store_id }} - 
                                    {{ $store->address->city->city }}, 
                                    {{ $store->address->city->country->country }}
                                </option>
                            @endforeach
                        </select>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Agregar Copias
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
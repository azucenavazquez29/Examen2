@extends('layouts.app')

@section('title', 'Gestión de Inventario')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-center my-4 display-5 fw-bold" style="color:white !important; font-weight:bolder !important;">
            <i class="fas fa-boxes me-2"></i>Gestión de Inventario Global
        </h1>
        <div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkAddModal">
                <i class="fas fa-plus-circle me-1"></i>Agregar Copias en Bulk
            </button>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Agregar Copia Individual
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar Película</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Título de película...">
                </div>
                <div class="col-md-3">
                    <label for="film_id" class="form-label">Película</label>
                    <select class="form-select" id="film_id" name="film_id">
                        <option value="">Todas las películas</option>
                        @foreach($films as $film)
                            <option value="{{ $film->film_id }}" 
                                    {{ request('film_id') == $film->film_id ? 'selected' : '' }}>
                                {{ $film->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="store_id" class="form-label">Tienda</label>
                    <select class="form-select" id="store_id" name="store_id">
                        <option value="">Todas las tiendas</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->store_id }}" 
                                    {{ request('store_id') == $store->store_id ? 'selected' : '' }}>
                                Tienda #{{ $store->store_id }} - {{ $store->address->city->city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen de Inventario Agrupado -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-2"></i>Resumen de Inventario por Película y Tienda
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Película</th>
                            <th>Tienda</th>
                            <th class="text-center">Total Copias</th>
                            <th class="text-center">Disponibles</th>
                            <th class="text-center">Rentadas</th>
                            <th class="text-center">% Disponibilidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryGrouped as $group)
                            @php
                                $rentedCopies = $group->total_copies - $group->available_copies;
                                $availability = $group->total_copies > 0 
                                    ? round(($group->available_copies / $group->total_copies) * 100) 
                                    : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $group->film->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $group->film->release_year }}</small>
                                </td>
                                <td>
                                    Tienda #{{ $group->store->store_id }}
                                    <br>
                                    <small class="text-muted">{{ $group->store->address->city->city }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $group->total_copies }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $group->available_copies }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">{{ $rentedCopies }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $availability >= 50 ? 'bg-success' : ($availability >= 25 ? 'bg-warning' : 'bg-danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $availability }}%">
                                            {{ $availability }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('inventory.by-film', $group->film_id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay inventario disponible</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Listado Detallado de Copias Individuales -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Listado Detallado de Copias</span>
            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#transferModal">
                <i class="fas fa-exchange-alt me-1"></i>Transferir Copias
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>ID Inventario</th>
                            <th>Película</th>
                            <th>Tienda</th>
                            <th>Estado</th>
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input inventory-checkbox" 
                                           value="{{ $item->inventory_id }}"
                                           {{ !$item->isAvailable() ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <code>#{{ $item->inventory_id }}</code>
                                </td>
                                <td>
                                    <strong>{{ $item->film->title }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $item->film->release_year }} | 
                                        {{ $item->film->rating }}
                                    </small>
                                </td>
                                <td>
                                    Tienda #{{ $item->store->store_id }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $item->store->address->city->city }}
                                    </small>
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
                                              onsubmit="return confirm('¿Está seguro de eliminar esta copia?')">
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
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No se encontraron copias</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $inventory->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal: Agregar Copias en Bulk -->
<div class="modal fade" id="bulkAddModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('inventory.bulk-add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Agregar Copias en Bulk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_film_id" class="form-label">Película *</label>
                        <select class="form-select" id="bulk_film_id" name="film_id" required>
                            <option value="">Seleccione una película</option>
                            @foreach($films as $film)
                                <option value="{{ $film->film_id }}">
                                    {{ $film->title }} ({{ $film->release_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bulk_store_id" class="form-label">Tienda *</label>
                        <select class="form-select" id="bulk_store_id" name="store_id" required>
                            <option value="">Seleccione una tienda</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->store_id }}">
                                    Tienda #{{ $store->store_id }} - {{ $store->address->city->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad de Copias *</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" 
                               min="1" max="50" value="5" required>
                        <small class="text-muted">Máximo: 50 copias</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Agregar Copias
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Transferir Copias -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('inventory.transfer') }}" method="POST" id="transferForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exchange-alt me-2"></i>Transferir Copias entre Tiendas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Seleccione las copias disponibles de la tabla y elija la tienda destino.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Copias Seleccionadas</label>
                        <div id="selectedCopiesCount" class="badge bg-primary">0 copias</div>
                    </div>
                    <div class="mb-3">
                        <label for="target_store_id" class="form-label">Tienda Destino *</label>
                        <select class="form-select" id="target_store_id" name="target_store_id" required>
                            <option value="">Seleccione la tienda destino</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->store_id }}">
                                    Tienda #{{ $store->store_id }} - {{ $store->address->city->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="selectedInventoryIds"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-exchange-alt me-1"></i>Transferir
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
    // Select All Checkbox
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.inventory-checkbox');
    
    selectAll?.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = this.checked;
            }
        });
        updateSelectedCount();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Update selected copies count
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.inventory-checkbox:checked');
        const count = selected.length;
        const countElement = document.getElementById('selectedCopiesCount');
        const form = document.getElementById('selectedInventoryIds');
        
        if (countElement) {
            countElement.textContent = `${count} ${count === 1 ? 'copia' : 'copias'}`;
        }

        // Clear previous inputs
        form.innerHTML = '';

        // Add hidden inputs for selected IDs
        selected.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'inventory_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
    }

    // Validate transfer form
    const transferForm = document.getElementById('transferForm');
    transferForm?.addEventListener('submit', function(e) {
        const selected = document.querySelectorAll('.inventory-checkbox:checked');
        if (selected.length === 0) {
            e.preventDefault();
            alert('Por favor, seleccione al menos una copia para transferir.');
        }
    });
});
</script>
@endpush
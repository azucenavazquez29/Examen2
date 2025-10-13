@extends('layouts.app')

@section('title', 'Editar Copia de Inventario')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Copia de Inventario #{{ $inventory->inventory_id }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(!$inventory->isAvailable())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>¡Advertencia!</strong> Esta copia está actualmente rentada y no puede ser modificada.
                            @if($inventory->currentRental())
                                <br>
                                <small>
                                    Cliente: {{ $inventory->currentRental()->customer->first_name }} 
                                    {{ $inventory->currentRental()->customer->last_name }}
                                    <br>
                                    Fecha de renta: {{ $inventory->currentRental()->rental_date->format('d/m/Y') }}
                                </small>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('inventory.update', $inventory->inventory_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">ID de Inventario</label>
                            <input type="text" class="form-control" value="#{{ $inventory->inventory_id }}" disabled>
                            <small class="text-muted">Este campo no se puede modificar</small>
                        </div>

                        <div class="mb-4">
                            <label for="film_id" class="form-label">
                                Película <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('film_id') is-invalid @enderror" 
                                    id="film_id" 
                                    name="film_id" 
                                    {{ !$inventory->isAvailable() ? 'disabled' : '' }}
                                    required>
                                <option value="">Seleccione una película</option>
                                @foreach($films as $film)
                                    <option value="{{ $film->film_id }}" 
                                            {{ (old('film_id', $inventory->film_id) == $film->film_id) ? 'selected' : '' }}>
                                        {{ $film->title }} 
                                        ({{ $film->release_year }}) - 
                                        {{ $film->rating }}
                                    </option>
                                @endforeach
                            </select>
                            @error('film_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="store_id" class="form-label">
                                Tienda <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('store_id') is-invalid @enderror" 
                                    id="store_id" 
                                    name="store_id" 
                                    {{ !$inventory->isAvailable() ? 'disabled' : '' }}
                                    required>
                                <option value="">Seleccione una tienda</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}" 
                                            {{ (old('store_id', $inventory->store_id) == $store->store_id) ? 'selected' : '' }}>
                                        Tienda #{{ $store->store_id }} - 
                                        {{ $store->address->city->city }}, 
                                        {{ $store->address->city->country->country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Para transferir copias masivamente, use la opción "Transferir Copias" desde el listado
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Estado Actual</label>
                            <div>
                                @if($inventory->isAvailable())
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle"></i> Disponible
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times-circle"></i> Rentada
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Última Actualización</label>
                            <input type="text" class="form-control" 
                                   value="{{ $inventory->last_update->format('d/m/Y H:i:s') }}" 
                                   disabled>
                        </div>

                        @if($inventory->isAvailable())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Nota:</strong> Al cambiar la película o tienda, se actualizará esta copia específica. 
                                Esta acción no afecta otras copias del inventario.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Volver al Listado
                            </a>
                            @if($inventory->isAvailable())
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i>Actualizar Copia
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="fas fa-lock me-1"></i>No Editable (Rentada)
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Información de la Copia
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Película Actual:</h6>
                            <p class="mb-2">
                                <strong>{{ $inventory->film->title }}</strong><br>
                                <small class="text-muted">
                                    Año: {{ $inventory->film->release_year }}<br>
                                    Clasificación: {{ $inventory->film->rating }}<br>
                                    Duración: {{ $inventory->film->length }} min<br>
                                    Costo de renta: ${{ number_format($inventory->film->rental_rate, 2) }}
                                </small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Tienda Actual:</h6>
                            <p class="mb-2">
                                <strong>Tienda #{{ $inventory->store->store_id }}</strong><br>
                                <small class="text-muted">
                                    {{ $inventory->store->address->address }}<br>
                                    {{ $inventory->store->address->city->city }}, 
                                    {{ $inventory->store->address->city->country->country }}<br>
                                    CP: {{ $inventory->store->address->postal_code }}
                                </small>
                            </p>
                        </div>
                    </div>

                    @if($inventory->rentals()->count() > 0)
                        <hr>
                        <h6>Historial de Rentas:</h6>
                        <p class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Esta copia ha sido rentada <strong>{{ $inventory->rentals()->count() }}</strong> 
                            {{ $inventory->rentals()->count() == 1 ? 'vez' : 'veces' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
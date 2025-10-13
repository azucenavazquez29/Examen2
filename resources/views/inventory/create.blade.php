@extends('layouts.app')

@section('title', 'Agregar Copia al Inventario')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Agregar Nueva Copia al Inventario
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="film_id" class="form-label">
                                Película <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('film_id') is-invalid @enderror" 
                                    id="film_id" 
                                    name="film_id" 
                                    required>
                                <option value="">Seleccione una película</option>
                                @foreach($films as $film)
                                    <option value="{{ $film->film_id }}" 
                                            {{ old('film_id') == $film->film_id ? 'selected' : '' }}>
                                        {{ $film->title }} 
                                        ({{ $film->release_year }}) - 
                                        {{ $film->rating }}
                                    </option>
                                @endforeach
                            </select>
                            @error('film_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Seleccione la película para agregar una copia al inventario
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="store_id" class="form-label">
                                Tienda <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('store_id') is-invalid @enderror" 
                                    id="store_id" 
                                    name="store_id" 
                                    required>
                                <option value="">Seleccione una tienda</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}" 
                                            {{ old('store_id') == $store->store_id ? 'selected' : '' }}>
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
                                Seleccione la tienda donde se agregará la copia
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Nota:</strong> Esta acción agregará una copia individual de la película seleccionada 
                            al inventario de la tienda especificada. Si desea agregar múltiples copias, 
                            use la opción "Agregar Copias en Bulk" desde el listado de inventario.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Guardar Copia
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ayuda Adicional -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-question-circle me-2"></i>Ayuda
                </div>
                <div class="card-body">
                    <h6>¿Qué hace esta acción?</h6>
                    <p class="mb-2">
                        Agregar una copia al inventario significa que está registrando una unidad física 
                        de una película que estará disponible para renta en la tienda seleccionada.
                    </p>
                    
                    <h6 class="mt-3">Consejos:</h6>
                    <ul class="mb-0">
                        <li>Cada copia tendrá un ID único de inventario</li>
                        <li>Las copias pueden ser transferidas entre tiendas posteriormente</li>
                        <li>Solo las copias disponibles (no rentadas) pueden ser editadas o eliminadas</li>
                        <li>Use "Agregar en Bulk" para agregar múltiples copias de una vez</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
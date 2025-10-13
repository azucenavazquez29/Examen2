@extends('layouts.app')

@section('title', 'Editar Tienda')

@section('content')
<div class="container-fluid py-4" style="color:white !important;">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">Tiendas</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('stores.show', $store->store_id) }}">Tienda #{{ $store->store_id }}</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
            <h1 class="text-center my-4 display-5 fw-bold" style="color:white !important; font-weight:bolder !important;">Editar Tienda #{{ $store->store_id }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <!-- Información del Gerente -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0" style="color:white !important;">
                        <i class="fas fa-user-tie me-2"></i>Gerente Actual
                    </h5>
                </div>
                <div class="card-body">
                    @if($store->manager)
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $store->manager->first_name }} {{ $store->manager->last_name }}</h6>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope me-2"></i>{{ $store->manager->email }}
                                </p>
                            </div>
                            <button type="button" 
                                    class="btn btn-outline-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#changeManagerModal">
                                <i class="fas fa-exchange-alt me-2"></i>Cambiar Gerente
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Esta tienda no tiene gerente asignado
                            <button type="button" 
                                    class="btn btn-sm btn-warning float-end" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#changeManagerModal">
                                Asignar Gerente
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Formulario de Edición -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Información de la Tienda
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stores.update', $store->store_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">
                                    Dirección <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="address" 
                                       id="address" 
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address', $store->address->address) }}"
                                       maxlength="50"
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="address2" class="form-label">Dirección 2 (opcional)</label>
                                <input type="text" 
                                       name="address2" 
                                       id="address2" 
                                       class="form-control @error('address2') is-invalid @enderror"
                                       value="{{ old('address2', $store->address->address2) }}"
                                       maxlength="50">
                                @error('address2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="district" class="form-label">
                                    Distrito <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="district" 
                                       id="district" 
                                       class="form-control @error('district') is-invalid @enderror"
                                       value="{{ old('district', $store->address->district) }}"
                                       maxlength="20"
                                       required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="city_id" class="form-label">
                                    Ciudad <span class="text-danger">*</span>
                                </label>
                                <select name="city_id" 
                                        id="city_id" 
                                        class="form-select @error('city_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Seleccione una ciudad --</option>
                                    @foreach(\App\Models\City::orderBy('city')->get() as $city)
                                        <option value="{{ $city->city_id }}" 
                                                {{ old('city_id', $store->address->city_id) == $city->city_id ? 'selected' : '' }}>
                                            {{ $city->city }}, {{ $city->country->country }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Código Postal</label>
                                <input type="text" 
                                       name="postal_code" 
                                       id="postal_code" 
                                       class="form-control @error('postal_code') is-invalid @enderror"
                                       value="{{ old('postal_code', $store->address->postal_code) }}"
                                       maxlength="10">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $store->address->phone) }}"
                                       maxlength="20"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('stores.show', $store->store_id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cambiar Gerente -->
<div class="modal fade" id="changeManagerModal" tabindex="-1" aria-labelledby="changeManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('stores.assign-manager', $store->store_id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="changeManagerModalLabel">
                        <i class="fas fa-user-tie me-2"></i>Cambiar Gerente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($store->manager)
                        <div class="alert alert-info">
                            <strong>Gerente actual:</strong> {{ $store->manager->first_name }} {{ $store->manager->last_name }}
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="manager_staff_id" class="form-label">
                            Nuevo Gerente <span class="text-danger">*</span>
                        </label>
                        <select name="manager_staff_id" 
                                id="manager_staff_id" 
                                class="form-select"
                                required>
                            <option value="">-- Seleccione un gerente --</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->staff_id }}">
                                    {{ $manager->first_name }} {{ $manager->last_name }} - {{ $manager->email }}
                                </option>
                            @endforeach
                        </select>
                        @if($managers->count() == 0)
                            <div class="alert alert-warning mt-2">
                                No hay empleados disponibles en esta tienda para asignar como gerente.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" {{ $managers->count() == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-check me-2"></i>Asignar Gerente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
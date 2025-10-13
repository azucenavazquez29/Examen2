@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Empleados</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.show', $staff->staff_id) }}">{{ $staff->full_name }}</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-user-edit me-2"></i>Editar Empleado</h2>
                @if($staff->isManager())
                    <span class="badge bg-warning text-dark fs-6">
                        <i class="fas fa-crown me-1"></i>Gerente
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.update', $staff->staff_id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Información Personal -->
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-user me-2 text-primary"></i>Información Personal
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name', $staff->first_name) }}"
                                       maxlength="45"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">
                                    Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name', $staff->last_name) }}"
                                       maxlength="45"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Información de Cuenta -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
                            <i class="fas fa-key me-2 text-primary"></i>Información de Cuenta
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $staff->email) }}"
                                       maxlength="50"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    Usuario <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username', $staff->username) }}"
                                       maxlength="16"
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Asignación de Tienda -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
                            <i class="fas fa-store me-2 text-primary"></i>Asignación de Tienda
                        </h5>
                        <div class="mb-3">
                            <label for="store_id" class="form-label">
                                Tienda <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('store_id') is-invalid @enderror" 
                                    id="store_id" 
                                    name="store_id" 
                                    required
                                    {{ $staff->isManager() ? 'disabled' : '' }}>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}" 
                                        {{ old('store_id', $staff->store_id) == $store->store_id ? 'selected' : '' }}>
                                        Tienda {{ $store->store_id }} - {{ $store->address->address ?? 'Sin dirección' }}
                                    </option>
                                @endforeach
                            </select>
                            @if($staff->isManager())
                                <input type="hidden" name="store_id" value="{{ $staff->store_id }}">
                                <small class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    No se puede cambiar la tienda de un gerente desde aquí
                                </small>
                            @endif
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>Dirección
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="address" class="form-label">
                                    Dirección <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('address') is-invalid @enderror" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address', $staff->address->address) }}"
                                       maxlength="50"
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address2" class="form-label">Dirección 2</label>
                                <input type="text" 
                                       class="form-control @error('address2') is-invalid @enderror" 
                                       id="address2" 
                                       name="address2" 
                                       value="{{ old('address2', $staff->address->address2) }}"
                                       maxlength="50">
                                @error('address2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="district" class="form-label">
                                    Distrito <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('district') is-invalid @enderror" 
                                       id="district" 
                                       name="district" 
                                       value="{{ old('district', $staff->address->district) }}"
                                       maxlength="20"
                                       required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="city_id" class="form-label">
                                    Ciudad <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('city_id') is-invalid @enderror" 
                                        id="city_id" 
                                        name="city_id" 
                                        required>
                                    <option value="{{ $staff->address->city_id }}">
                                        {{ $staff->address->city->city ?? 'Seleccione ciudad' }}
                                    </option>
                                </select>
                                @error('city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="postal_code" class="form-label">Código Postal</label>
                                <input type="text" 
                                       class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       value="{{ old('postal_code', $staff->address->postal_code) }}"
                                       maxlength="10">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $staff->address->phone) }}"
                                   maxlength="20"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('staff.show', $staff->staff_id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Acciones Adicionales -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Acciones Adicionales</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Resetear contraseña -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6><i class="fas fa-key me-2 text-warning"></i>Resetear Contraseña</h6>
                                <p class="text-muted small mb-2">Generar una nueva contraseña temporal para el empleado</p>
                                <form action="{{ route('staff.reset-password', $staff->staff_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" 
                                            onclick="return confirm('¿Está seguro de resetear la contraseña?')">
                                        <i class="fas fa-sync me-1"></i>Resetear Contraseña
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Cambiar estado -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6>
                                    <i class="fas fa-toggle-on me-2 text-info"></i>Estado de la Cuenta
                                </h6>
                                <p class="text-muted small mb-2">
                                    Estado actual: 
                                    <strong class="{{ $staff->active ? 'text-success' : 'text-secondary' }}">
                                        {{ $staff->active ? 'Activo' : 'Inactivo' }}
                                    </strong>
                                </p>
                                <form action="{{ route('staff.toggle-active', $staff->staff_id) }}" method="POST">
                                    @csrf
                                    @if($staff->active)
                                        <button type="submit" class="btn btn-secondary btn-sm"
                                                onclick="return confirm('¿Desactivar este empleado?')">
                                            <i class="fas fa-ban me-1"></i>Desactivar
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('¿Activar este empleado?')">
                                            <i class="fas fa-check me-1"></i>Activar
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>

                        <!-- Cambiar tienda (si no es gerente) -->
                        @if(!$staff->isManager())
                        <div class="col-md-12">
                            <div class="border rounded p-3">
                                <h6><i class="fas fa-exchange-alt me-2 text-primary"></i>Cambiar de Tienda</h6>
                                <form action="{{ route('staff.change-store', $staff->staff_id) }}" 
                                      method="POST" 
                                      class="row g-2 align-items-end">
                                    @csrf
                                    <div class="col-md-8">
                                        <label class="form-label small">Nueva tienda</label>
                                        <select name="store_id" class="form-select form-select-sm" required>
                                            @foreach($stores as $store)
                                                @if($store->store_id != $staff->store_id)
                                                <option value="{{ $store->store_id }}">
                                                    Tienda {{ $store->store_id }} - {{ $store->address->address ?? 'Sin dirección' }}
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-check me-1"></i>Cambiar Tienda
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
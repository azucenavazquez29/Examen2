@extends('layouts.app')

@section('title', 'Crear Nuevo Empleado')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3">Crear Nuevo Empleado</h1>
        </div>
    </div>

    <!-- Alertas de Error -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> 
            <strong>Error en el formulario:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('staff.store') }}" method="POST">
                        @csrf

                        <!-- Información Personal -->
                        <h5 class="mb-3">
                            <i class="fas fa-user"></i> Información Personal
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="first_name" 
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}" 
                                       maxlength="45"
                                       placeholder="Ej: Juan"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Apellido *</label>
                                <input type="text" name="last_name" 
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}" 
                                       maxlength="45"
                                       placeholder="Ej: Pérez"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" 
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" 
                                       maxlength="50"
                                       placeholder="email@ejemplo.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Usuario *</label>
                                <input type="text" name="username" 
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username') }}" 
                                       maxlength="16"
                                       placeholder="Ej: jperez"
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Máximo 16 caracteres, sin espacios</small>
                            </div>
                        </div>

                        <!-- Asignación de Tienda -->
                        <h5 class="mb-3 mt-4">
                            <i class="fas fa-store"></i> Asignación de Tienda
                        </h5>

                        <div class="mb-3">
                            <label class="form-label">Tienda *</label>
                            <select name="store_id" 
                                    class="form-select @error('store_id') is-invalid @enderror" 
                                    required>
                                <option value="">-- Selecciona una tienda --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}"
                                        {{ old('store_id') == $store->store_id ? 'selected' : '' }}>
                                        Tienda {{ $store->store_id }} - {{ $store->address->city->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información de Dirección -->
                        <h5 class="mb-3 mt-4">
                            <i class="fas fa-map-marker-alt"></i> Dirección
                        </h5>

                        <div class="mb-3">
                            <label class="form-label">Dirección Principal *</label>
                            <input type="text" name="address" 
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" 
                                   maxlength="50"
                                   placeholder="Calle, número, etc."
                                   required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección Secundaria</label>
                            <input type="text" name="address2" 
                                   class="form-control"
                                   value="{{ old('address2') }}" 
                                   maxlength="50"
                                   placeholder="Apto, suite, etc. (opcional)">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Distrito *</label>
                                <input type="text" name="district" 
                                       class="form-control @error('district') is-invalid @enderror"
                                       value="{{ old('district') }}" 
                                       maxlength="20"
                                       placeholder="Ej: Centro"
                                       required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ciudad *</label>
                                <select name="city_id" 
                                        class="form-select @error('city_id') is-invalid @enderror" 
                                        required>
                                    <option value="">-- Selecciona una ciudad --</option>
                                    @foreach($cities ?? [] as $city)
                                        <option value="{{ $city->city_id }}"
                                            {{ old('city_id') == $city->city_id ? 'selected' : '' }}>
                                            {{ $city->city }}, {{ $city->country->country }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Código Postal</label>
                                <input type="text" name="postal_code" 
                                       class="form-control"
                                       value="{{ old('postal_code') }}" 
                                       maxlength="10"
                                       placeholder="Ej: 28001">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Teléfono *</label>
                                <input type="text" name="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" 
                                       maxlength="20"
                                       placeholder="+34 123 456 789"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Empleado
                            </button>
                            <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Información -->
        <div class="col-lg-4">
            <!-- Información Importante -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb"></i> Información Importante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="small">
                            <i class="fas fa-key"></i> Contraseña Temporal
                        </h6>
                        <p class="small text-muted mb-0">
                            Se generará una contraseña temporal automáticamente. Asegúrate de copiarla después de crear el empleado para enviársela.
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6 class="small">
                            <i class="fas fa-user-tie"></i> Datos Requeridos
                        </h6>
                        <p class="small text-muted mb-0">
                            Todos los campos marcados con * son obligatorios. Asegúrate de ingresarlos correctamente.
                        </p>
                    </div>

                    <div class="mb-0">
                        <h6 class="small">
                            <i class="fas fa-check-circle"></i> Email Único
                        </h6>
                        <p class="small text-muted mb-0">
                            El email y usuario deben ser únicos en el sistema. No pueden repetirse.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Campos Requeridos -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-list"></i> Campos Requeridos
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small text-muted mb-0">
                        <li>Nombre</li>
                        <li>Apellido</li>
                        <li>Email (único)</li>
                        <li>Usuario (único, máx 16 caracteres)</li>
                        <li>Tienda</li>
                        <li>Dirección Principal</li>
                        <li>Distrito</li>
                        <li>Ciudad</li>
                        <li>Teléfono</li>
                    </ul>
                </div>
            </div>

            <!-- Consejos -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-clipboard-list"></i> Consejos
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small text-muted mb-0">
                        <li>Usa un nombre de usuario corto y memorable</li>
                        <li>Verifica que el email sea correcto para enviar credenciales</li>
                        <li>Selecciona la tienda correcta antes de guardar</li>
                        <li>La dirección debe ser la del empleado o la de la tienda</li>
                        <li>Asegúrate de copiar la contraseña temporal</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
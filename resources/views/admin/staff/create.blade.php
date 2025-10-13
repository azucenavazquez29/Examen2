@extends('layouts.app')

@section('title', 'Crear Nuevo Empleado')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Empleados</a></li>
                    <li class="breadcrumb-item active">Crear Nuevo</li>
                </ol>
            </nav>
            <h2><i class="fas fa-user-plus me-2"></i>Crear Nuevo Empleado</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.store') }}">
                        @csrf

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
                                       value="{{ old('first_name') }}"
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
                                       value="{{ old('last_name') }}"
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
                                       value="{{ old('email') }}"
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
                                       value="{{ old('username') }}"
                                       maxlength="16"
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Máximo 16 caracteres</small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Se generará una contraseña temporal automáticamente que se mostrará al finalizar.
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
                                    required>
                                <option value="">Seleccione una tienda</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}" 
                                        {{ old('store_id') == $store->store_id ? 'selected' : '' }}>
                                        Tienda {{ $store->store_id }} - {{ $store->address->address ?? 'Sin dirección' }}
                                    </option>
                                @endforeach
                            </select>
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
                                       value="{{ old('address') }}"
                                       maxlength="50"
                                       placeholder="Calle y número"
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
                                       value="{{ old('address2') }}"
                                       maxlength="50"
                                       placeholder="Apartamento, suite, etc.">
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
                                       value="{{ old('district') }}"
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
                                    <option value="">Seleccione una ciudad</option>
                                    @foreach($cities as $city)
                                    <option value="{{$city->city_id}}">{{ $city->city_id }} {{ $city->city }}</option>
                                    @endforeach
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
                                       value="{{ old('postal_code') }}"
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
                                   value="{{ old('phone') }}"
                                   maxlength="20"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Crear Empleado
                            </button>
                            <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Puedes agregar lógica para cargar ciudades dinámicamente
    document.addEventListener('DOMContentLoaded', function() {
        // Ejemplo: fetch ciudades via AJAX
        // o cargarlas desde el controlador
    });
</script>
@endpush
@endsection
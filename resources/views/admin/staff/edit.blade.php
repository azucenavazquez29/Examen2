@extends('layouts.app')

@section('title', 'Editar Empleado: ' . $staff->full_name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3">Editar Empleado: {{ $staff->full_name }}</h1>
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

    <!-- Alertas de Éxito -->
    @if($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('staff.update', $staff->staff_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Información Personal -->
                        <h5 class="mb-3">
                            <i class="fas fa-user"></i> Información Personal
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="first_name" 
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $staff->first_name) }}" 
                                       maxlength="45"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Apellido *</label>
                                <input type="text" name="last_name" 
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $staff->last_name) }}" 
                                       maxlength="45"
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
                                       value="{{ old('email', $staff->email) }}" 
                                       maxlength="50"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Usuario</label>
                                <input type="text" name="username" 
                                       class="form-control"
                                       value="{{ $staff->username }}" 
                                       maxlength="16"
                                       disabled
                                       title="El nombre de usuario no puede editarse">
                                <small class="text-muted">El usuario no puede cambiarse</small>
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
                                        {{ old('store_id', $staff->store_id) == $store->store_id ? 'selected' : '' }}>
                                        Tienda {{ $store->store_id }} - {{ $store->address->city->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($staff->isManager())
                                <small class="text-warning">
                                    <i class="fas fa-star"></i> Este empleado es gerente de esta tienda
                                </small>
                            @endif
                        </div>

                        <!-- Información de Dirección -->
                        <h5 class="mb-3 mt-4">
                            <i class="fas fa-map-marker-alt"></i> Dirección
                        </h5>

                        <div class="mb-3">
                            <label class="form-label">Dirección Principal *</label>
                            <input type="text" name="address" 
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $staff->address->address) }}" 
                                   maxlength="50"
                                   required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección Secundaria</label>
                            <input type="text" name="address2" 
                                   class="form-control"
                                   value="{{ old('address2', $staff->address->address2) }}" 
                                   maxlength="50">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Distrito *</label>
                                <input type="text" name="district" 
                                       class="form-control @error('district') is-invalid @enderror"
                                       value="{{ old('district', $staff->address->district) }}" 
                                       maxlength="20"
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
                                            {{ old('city_id', $staff->address->city_id) == $city->city_id ? 'selected' : '' }}>
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
                                       value="{{ old('postal_code', $staff->address->postal_code) }}" 
                                       maxlength="10">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Teléfono *</label>
                                <input type="text" name="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $staff->address->phone) }}" 
                                       maxlength="20"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('staff.show', $staff->staff_id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Información de Cuenta -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user-circle"></i> Información de Cuenta
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Estado</label>
                        <div>
                            @if($staff->active)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-ban"></i> Inactivo
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Rol</label>
                        <div>
                            @if($staff->isManager())
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-crown"></i> Gerente
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user"></i> Empleado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">ID Staff</label>
                        <p class="mb-0"><code>{{ $staff->staff_id }}</code></p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Registrado</label>
                        <p class="mb-0">{{ $staff->created_at ? $staff->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Información de Tienda -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-store"></i> Tienda Asignada
                    </h6>
                </div>
                <div class="card-body">
                    @if($staff->store)
                        <div class="mb-2">
                            <label class="text-muted small">Tienda</label>
                            <p class="mb-0">
                                <strong>Tienda {{ $staff->store->store_id }}</strong>
                            </p>
                        </div>

                        <div class="mb-2">
                            <label class="text-muted small">Ubicación</label>
                            <p class="mb-0">
                                <small>{{ $staff->store->address->city->city }}<br>
                                {{ $staff->store->address->city->country->country }}</small>
                            </p>
                        </div>

                        <a href="{{ route('stores.show', $staff->store->store_id) }}" class="btn btn-sm btn-outline-primary w-100">
                            Ver Tienda
                        </a>
                    @else
                        <p class="text-muted">Sin tienda asignada</p>
                    @endif
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar"></i> Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Rentas Procesadas</label>
                        <p class="mb-0">
                            <strong class="text-primary">{{ $staff->rentals()->count() }}</strong>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Rentas del Mes</label>
                        <p class="mb-0">
                            <strong class="text-info">{{ $staff->monthlyRentalsCount() }}</strong>
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Ingresos del Mes</label>
                        <p class="mb-0">
                            <strong class="text-success">${{ number_format($staff->monthlyIncome(), 2) }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('staff.show', $staff->staff_id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Ver Detalle Completo
                    </a>

                    <button type="button" class="btn btn-outline-warning btn-sm" 
                            onclick="if(confirm('¿Resetear contraseña?')) document.getElementById('resetPasswordForm').submit();">
                        <i class="fas fa-key"></i> Resetear Contraseña
                    </button>

                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>

            <!-- Formulario oculto para resetear contraseña -->
            <form id="resetPasswordForm" action="{{ route('staff.reset-password', $staff->staff_id) }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection
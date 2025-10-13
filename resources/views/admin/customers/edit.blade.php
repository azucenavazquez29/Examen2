@extends('layouts.app')

@section('title', 'Editar Cliente: ' . $customer->full_name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3">
                Editar Cliente: {{ $customer->full_name }}
            </h1>
        </div>
    </div>

    <!-- Alertas -->
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
                    <form action="{{ route('admin.customers.update', $customer->customer_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Información Personal -->
                        <h5 class="mb-3">Información Personal</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="first_name" 
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $customer->first_name) }}" 
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Apellido *</label>
                                <input type="text" name="last_name" 
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $customer->last_name) }}" 
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Asignación de Tienda -->
                        <h5 class="mb-3 mt-4">Asignación de Tienda</h5>

                        <div class="mb-3">
                            <label class="form-label">Tienda *</label>
                            <select name="store_id" 
                                    class="form-select @error('store_id') is-invalid @enderror" 
                                    required>
                                <option value="">Selecciona una tienda</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}"
                                        {{ old('store_id', $customer->store_id) == $store->store_id ? 'selected' : '' }}>
                                        Tienda {{ $store->store_id }} - {{ $store->address->city->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('admin.customers.show', $customer->customer_id) }}" 
                               class="btn btn-secondary">
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
                <div class="card-header">
                    <h6 class="mb-0">Información de Cuenta</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Estado</label>
                        <div>
                            @if($customer->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">ID Cliente</label>
                        <p class="mb-0"><code>{{ $customer->customer_id }}</code></p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Registrado</label>
                        <p class="mb-0">{{ $customer->create_date->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Última Actualización</label>
                        <p class="mb-0">{{ $customer->updated_at ? $customer->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Información de Dirección -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Dirección</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="text-muted small">Dirección</label>
                        <p class="mb-0">
                            <small>{{ $customer->address->address }}</small>
                        </p>
                    </div>

                    @if($customer->address->address2)
                        <div class="mb-2">
                            <label class="text-muted small">Dirección 2</label>
                            <p class="mb-0">
                                <small>{{ $customer->address->address2 }}</small>
                            </p>
                        </div>
                    @endif

                    <div class="mb-2">
                        <label class="text-muted small">Ubicación</label>
                        <p class="mb-0">
                            <small>{{ $customer->address->district }}<br>
                            {{ $customer->address->city->city }}, {{ $customer->address->city->country->country }}</small>
                        </p>
                    </div>

                    @if($customer->address->postal_code)
                        <div class="mb-2">
                            <label class="text-muted small">Código Postal</label>
                            <p class="mb-0">
                                <small>{{ $customer->address->postal_code }}</small>
                            </p>
                        </div>
                    @endif

                    <div class="mb-0">
                        <label class="text-muted small">Teléfono</label>
                        <p class="mb-0">
                            <small><i class="fas fa-phone"></i> {{ $customer->address->phone }}</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Estadísticas</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Rentas Totales</label>
                        <p class="mb-0">
                            <strong class="text-primary">{{ $customer->rentals()->count() }}</strong>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Rentas Activas</label>
                        <p class="mb-0">
                            <strong class="text-warning">{{ $customer->rentals()->whereNull('return_date')->count() }}</strong>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Total Gastado</label>
                        <p class="mb-0">
                            <strong class="text-success">${{ number_format($customer->totalSpent(), 2) }}</strong>
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Rentas Vencidas</label>
                        <p class="mb-0">
                            <strong class="text-danger">
                                @php
                                    $overdueCount = $customer->rentals()
                                        ->whereNull('return_date')
                                        ->whereRaw('DATE_ADD(rental_date, INTERVAL (SELECT rental_duration FROM film WHERE film.film_id = inventory.film_id) DAY) < NOW()')
                                        ->count();
                                @endphp
                                {{ $overdueCount }}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
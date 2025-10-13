@extends('layouts.app')

@section('title', 'Crear Nueva Tienda')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">Tiendas</a></li>
                    <li class="breadcrumb-item active">Nueva Tienda</li>
                </ol>
            </nav>
            <h2>Crear Nueva Tienda</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('stores.store') }}" method="POST">
                        @csrf

                        <!-- Gerente -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user-tie me-2"></i>Gerente de la Tienda
                            </h5>
                            
                            <div class="mb-3">
                                <label for="manager_staff_id" class="form-label">
                                    Seleccionar Gerente <span class="text-danger">*</span>
                                </label>
                                <select name="manager_staff_id" 
                                        id="manager_staff_id" 
                                        class="form-select @error('manager_staff_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Seleccione un gerente --</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->staff_id }}" {{ old('manager_staff_id') == $manager->staff_id ? 'selected' : '' }}>
                                            {{ $manager->first_name }} {{ $manager->last_name }} - {{ $manager->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('manager_staff_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($managers->count() == 0)
                                    <div class="alert alert-warning mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        No hay empleados disponibles para asignar como gerente.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información de Dirección -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Información de Dirección
                            </h5>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">
                                        Dirección <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="address" 
                                           id="address" 
                                           class="form-control @error('address') is-invalid @enderror"
                                           value="{{ old('address') }}"
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
                                           value="{{ old('address2') }}"
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
                                           value="{{ old('district') }}"
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
                                            <option value="{{ $city->city_id }}" {{ old('city_id') == $city->city_id ? 'selected' : '' }}>
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
                                           value="{{ old('postal_code') }}"
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
                                           value="{{ old('phone') }}"
                                           maxlength="20"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Crear Tienda
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Opcional: Agregar búsqueda en select de ciudad
    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('city_id');
        if (citySelect && citySelect.options.length > 50) {
            // Si hay muchas ciudades, podrías agregar Select2 o similar
            console.log('Consider adding Select2 or similar for better UX');
        }
    });
</script>
@endpush
@extends('layouts.app')

@section('title', 'Registrar Nuevo Cliente')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="color:white !important;" class="h3 fw-bold text-dark">Registrar Nuevo Cliente</h1>
            <p style="color:white !important;" class="text-muted mt-2">Completa todos los campos del formulario</p>
        </div>
        <a href="{{ route('empleado.clientes.index') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST" class="card shadow-sm">
        @csrf
        
        <div class="card-body p-4">
            <div class="row g-3">
                <!-- Información Personal -->
                <div class="col-12">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Información Personal</h5>
                </div>

                <div class="col-md-6">
                    <label for="first_name" class="form-label fw-medium">
                        Nombre <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="first_name" 
                        id="first_name" 
                        value="{{ old('first_name') }}"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label for="last_name" class="form-label fw-medium">
                        Apellido <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="last_name" 
                        id="last_name" 
                        value="{{ old('last_name') }}"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-12">
                    <label for="email" class="form-label fw-medium">
                        Correo Electrónico <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        class="form-control"
                        required
                    >
                    <div class="form-text">El email debe ser único en el sistema</div>
                </div>

                <!-- Selección de Dirección -->
                <div class="col-12 mt-4">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Dirección</h5>
                </div>

                <div class="col-12">
                    <label for="address_id" class="form-label fw-medium">
                        Dirección <span class="text-danger">*</span>
                    </label>
                    <select 
                        name="address_id" 
                        id="address_id" 
                        class="form-select"
                        required
                    >
                        <option value="">Selecciona una dirección</option>
                        @foreach($addresses as $address)
                            <option value="{{ $address->address_id }}" {{ old('address_id') == $address->address_id ? 'selected' : '' }}>
                                {{ $address->address }}
                                @if($address->address2), {{ $address->address2 }}@endif
                                - {{ $address->district }}
                                @if($address->postal_code) (CP: {{ $address->postal_code }})@endif
                                - Tel: {{ $address->phone }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Selecciona la dirección del cliente</div>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('empleado.clientes.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    Registrar Cliente
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Búsqueda en select de dirección
    document.getElementById('address_id').addEventListener('change', function() {
        console.log('Dirección seleccionada:', this.value);
    });
</script>
@endsection
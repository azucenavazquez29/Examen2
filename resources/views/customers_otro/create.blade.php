@include('parts.head')
@include('parts.header')

<div id="cont_principal">
    <div class="container-fluid" id="container_a">
        @include('parts.nav')

        <div class="container my-5">
            <div class="card shadow-lg rounded-4 border-0 bg-dark text-white">
                <div class="card-header bg-gradient bg-dark text-warning text-center py-4">
                    <h1 class="display-5 fw-bold">👤 Nuevo Cliente</h1>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('customers_otro.store') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <h4 class="text-warning mb-3">📋 Información del Cliente</h4>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="first_name" class="form-label fw-semibold text-warning">Nombres</label>
                                <input type="text" name="first_name" id="first_name"
                                    class="form-control form-control-lg shadow-sm bg-secondary text-white border-0"
                                    placeholder="Nombre del cliente" value="{{ old('first_name') }}" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="last_name" class="form-label fw-semibold text-warning">Apellidos</label>
                                <input type="text" name="last_name" id="last_name"
                                    class="form-control form-control-lg shadow-sm bg-secondary text-white border-0"
                                    placeholder="Apellido del cliente" value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-semibold text-warning">Correo (Email)</label>
                                <input type="email" name="email" id="email"
                                    class="form-control shadow-sm bg-secondary text-white border-0"
                                    placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="store_id" class="form-label fw-semibold text-warning">Tienda</label>
                                <select name="store_id" id="store_id"
                                    class="form-select shadow-sm bg-secondary text-white border-0" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->store_id }}" {{ old('store_id') == $store->store_id ? 'selected' : '' }}>
                                            Tienda {{ $store->store_id }} - {{ $store->address->city->city ?? 'Sin ciudad' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active" id="active" 
                                    value="1" {{ old('active', true) ? 'checked' : '' }}>
                                <label class="form-check-label text-warning fw-semibold" for="active">
                                    Cliente Activo
                                </label>
                            </div>
                        </div>

                        <hr class="border-warning my-4">

                        <h4 class="text-warning mb-3">📍 Dirección</h4>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold text-warning">Dirección</label>
                            <input type="text" name="address" id="address"
                                class="form-control shadow-sm bg-secondary text-white border-0"
                                placeholder="Calle, número, colonia" value="{{ old('address') }}">
                        </div>

                        <div class="mb-4">
                            <label for="address2" class="form-label fw-semibold text-warning">Dirección 2 (Opcional)</label>
                            <input type="text" name="address2" id="address2"
                                class="form-control shadow-sm bg-secondary text-white border-0"
                                placeholder="Departamento, piso, etc." value="{{ old('address2') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="district" class="form-label fw-semibold text-warning">Distrito</label>
                                <input type="text" name="district" id="district"
                                    class="form-control shadow-sm bg-secondary text-white border-0"
                                    placeholder="Distrito" value="{{ old('district') }}" required>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="city_id" class="form-label fw-semibold text-warning">Ciudad</label>
                                <select name="city_id" id="city_id"
                                    class="form-select shadow-sm bg-secondary text-white border-0" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->city_id }}" {{ old('city_id') == $city->city_id ? 'selected' : '' }}>
                                            {{ $city->city }} - {{ $city->country->country ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="postal_code" class="form-label fw-semibold text-warning">Código Postal</label>
                                <input type="text" name="postal_code" id="postal_code"
                                    class="form-control shadow-sm bg-secondary text-white border-0"
                                    placeholder="00000" value="{{ old('postal_code') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold text-warning">Teléfono</label>
                            <input type="text" name="phone" id="phone"
                                class="form-control shadow-sm bg-secondary text-white border-0"
                                placeholder="000-000-0000" value="{{ old('phone') }}" required>
                        </div>

                        <div class="text-center mt-5">
                            <a href="{{ route('customers_otro.index') }}" class="btn btn-secondary btn-lg shadow-lg px-5 py-3 fw-bold me-3">
                                ← Cancelar
                            </a>
                            <button type="submit"
                                class="btn btn-warning btn-lg shadow-lg px-5 py-3 fw-bold text-dark">
                                💾 Guardar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('parts.footer')
        @include('parts.scripts')
    </div>
</div>
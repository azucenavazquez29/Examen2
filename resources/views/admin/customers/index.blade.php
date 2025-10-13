@extends('layouts.app')

@section('title', 'Tienda ' . $store->store_id)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Tienda {{ $store->store_id }}</h1>
                <div class="btn-group">
                    <a href="{{ route('stores.edit', $store->store_id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información General -->
        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">ID Tienda</label>
                        <p class="mb-0"><strong>{{ $store->store_id }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Estado</label>
                        <p class="mb-0">
                            <span class="badge bg-success">Activa</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dirección -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Ubicación</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">{{ $store->address->address }}</p>
                    @if($store->address->address2)
                        <p class="mb-2">{{ $store->address->address2 }}</p>
                    @endif
                    <p class="mb-2">
                        <strong>{{ $store->address->district }}</strong>
                    </p>
                    <p class="mb-2">
                        {{ $store->address->city->city }}, {{ $store->address->city->country->country }}
                    </p>
                    @if($store->address->postal_code)
                        <p class="mb-2">CP: {{ $store->address->postal_code }}</p>
                    @endif
                    <p class="mb-0">
                        <i class="fas fa-phone"></i> {{ $store->address->phone }}
                    </p>
                </div>
            </div>

            <!-- Gerente -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Gerente</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>{{ $store->manager->full_name }}</strong>
                    </p>
                    <p class="mb-2">
                        <small class="text-muted">{{ $store->manager->email }}</small>
                    </p>
                    <p class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-user"></i> {{ $store->manager->username }}
                        </small>
                    </p>
                    <a href="{{ route('staff.show', $store->manager->staff_id) }}" class="btn btn-sm btn-outline-primary w-100">
                        Ver Perfil del Gerente
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas y Operaciones -->
        <div class="col-lg-6">
            <!-- Estadísticas principales -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-primary">{{ $stats['active_staff'] }}/{{ $stats['total_staff'] }}</h4>
                            <p class="text-muted mb-0">Empleados Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-info">{{ $stats['active_customers'] }}/{{ $stats['total_customers'] }}</h4>
                            <p class="text-muted mb-0">Clientes Activos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-success">{{ $stats['total_films'] }}</h4>
                            <p class="text-muted mb-0">Películas Disponibles</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-warning">{{ $stats['total_inventory'] }}</h4>
                            <p class="text-muted mb-0">Copias en Inventario</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-success">{{ $stats['active_rentals'] }}</h4>
                            <p class="text-muted mb-0">Rentas Activas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-danger">{{ $stats['overdue_rentals'] }}</h4>
                            <p class="text-muted mb-0">Rentas Vencidas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empleados de la tienda -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Empleados ({{ $store->staff->count() }})</h5>
                    <a href="{{ route('staff.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Nuevo
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($store->staff as $employee)
                                <tr>
                                    <td><strong>{{ $employee->full_name }}</strong></td>
                                    <td><code>{{ $employee->username }}</code></td>
                                    <td>
                                        @if($employee->isManager())
                                            <span class="badge bg-warning">Gerente</span>
                                        @else
                                            <span class="badge bg-secondary">Empleado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->active)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('staff.show', $employee->staff_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No hay empleados en esta tienda
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Películas más rentadas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Películas Más Rentadas (Top 5)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Película</th>
                                <th>Rentas</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($store->topRentedFilms(5) as $film)
                                <tr>
                                    <td>{{ $film->title }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $film->rentals()->where('inventory_id', '!=', 0)->count() }}
                                        </span>
                                    </td>
                                    <td>{{ $film->rating }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        No hay rentas registradas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('staff.by-store', $store->store_id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-users"></i> Ver Empleados
                    </a>

                    <a href="{{ route('admin.customers.by-store', $store->store_id) }}" class="btn btn-outline-info">
                        <i class="fas fa-user-friends"></i> Ver Clientes
                    </a>

                    <a href="{{ route('inventory.by-store', $store->store_id) }}" class="btn btn-outline-success">
                        <i class="fas fa-boxes"></i> Ver Inventario
                    </a>

                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#assignManagerModal">
                        <i class="fas fa-crown"></i> Cambiar Gerente
                    </button>

                    <a href="{{ route('stores.edit', $store->store_id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-edit"></i> Editar Tienda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar gerente -->
<div class="modal fade" id="assignManagerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Nuevo Gerente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('stores.assign-manager', $store->store_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Selecciona al nuevo gerente:</label>
                        <select name="manager_staff_id" class="form-select" required>
                            <option value="">-- Selecciona un empleado --</option>
                            @foreach(\App\Models\Staff::where('active', true)->whereNotIn('staff_id', [$store->manager->staff_id])->get() as $employee)
                                <option value="{{ $employee->staff_id }}">
                                    {{ $employee->full_name }} ({{ $employee->username }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asignar Gerente</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
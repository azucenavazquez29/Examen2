@extends('layouts.app')

@section('title', 'Detalle de Empleado: ' . $staff->full_name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">{{ $staff->full_name }}</h1>
                <div class="btn-group">
                    <a href="{{ route('staff.edit', $staff->staff_id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('staff.index') }}" class="btn btn-secondary">
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
            @if($new_password = session('new_password'))
                <br><small>Nueva contraseña temporal: <code>{{ $new_password }}</code></small>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nombre Completo</label>
                        <p class="mb-0">
                        <i class="fas fa-phone"></i> {{ $staff->address->phone }}
                    </p>
                </div>
            </div>

            <!-- Tienda -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tienda Asignada</h5>
                </div>
                <div class="card-body">
                    @if($staff->store)
                        <p class="mb-2">
                            <strong>Tienda {{ $staff->store->store_id }}</strong>
                        </p>
                        <p class="mb-2">
                            <small class="text-muted">{{ $staff->store->address->address }}</small>
                        </p>
                        <a href="{{ route('stores.show', $staff->store->store_id) }}" class="btn btn-sm btn-outline-primary w-100">
                            Ver Tienda
                        </a>
                    @else
                        <p class="text-muted">Sin tienda asignada</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas y Actividad -->
        <div class="col-lg-6">
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-primary">{{ $stats['total_rentals'] }}</h4>
                            <p class="text-muted mb-0">Rentas Totales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-info">{{ $stats['active_rentals'] }}</h4>
                            <p class="text-muted mb-0">Rentas Activas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rentas Recientes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Rentas Recientes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Película</th>
                                <th>Cliente</th>
                                <th>Fecha Renta</th>
                                <th>Devolución</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff->rentals()->latest('rental_date')->limit(10)->get() as $rental)
                                <tr>
                                    <td>
                                        <small>{{ $rental->inventory->film->title }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $rental->customer->full_name }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $rental->rental_date->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            @if($rental->return_date)
                                                {{ $rental->return_date->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-danger">Pendiente</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($rental->return_date)
                                            <span class="badge bg-success">Devuelto</span>
                                        @else
                                            <span class="badge bg-warning">Activo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No hay rentas registradas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Acciones de Seguridad -->
        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Acciones de Seguridad</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <!-- Reset Password -->
                    <form action="{{ route('staff.reset-password', $staff->staff_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100"
                                onclick="return confirm('¿Estás seguro de resetear la contraseña?')">
                            <i class="fas fa-key"></i> Resetear Contraseña
                        </button>
                    </form>

                    <!-- Toggle Active -->
                    <form action="{{ route('staff.toggle-active', $staff->staff_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $staff->active ? 'btn-danger' : 'btn-success' }} w-100"
                                onclick="return confirm('¿{{ $staff->active ? 'Desactivar' : 'Activar' }} este empleado?')">
                            <i class="fas {{ $staff->active ? 'fa-ban' : 'fa-check' }}"></i>
                            {{ $staff->active ? 'Desactivar Cuenta' : 'Activar Cuenta' }}
                        </button>
                    </form>

                    <!-- Change Store -->
                    @if(!$staff->isManager())
                        <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#changeStoreModal">
                            <i class="fas fa-exchange-alt"></i> Cambiar Tienda
                        </button>
                    @endif

                    @if(!$staff->isManager())
                        <!-- Delete -->
                        <form action="{{ route('staff.destroy', $staff->staff_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('¿Estás seguro de eliminar este empleado?')">
                                <i class="fas fa-trash"></i> Eliminar Empleado
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Información de Timestamps -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Creado</label>
                        <p class="mb-0 small">{{ $staff->created_at ? $staff->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Última Actualización</label>
                        <p class="mb-0 small">{{ $staff->updated_at ? $staff->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">ID de Staff</label>
                        <p class="mb-0 small"><code>{{ $staff->staff_id }}</code></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar tienda -->
<div class="modal fade" id="changeStoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.change-store', $staff->staff_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Selecciona la nueva tienda:</label>
                        <select name="store_id" class="form-select" required>
                            <option value="">-- Selecciona una tienda --</option>
                            @foreach($staff->store ? \App\Models\Store::where('store_id', '!=', $staff->store_id)->get() : \App\Models\Store::all() as $store)
                                <option value="{{ $store->store_id }}">
                                    Tienda {{ $store->store_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Cambiar Tienda</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection<strong>{{ $staff->full_name }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Usuario</label>
                        <p class="mb-0"><code>{{ $staff->username }}</code></p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $staff->email }}">{{ $staff->email }}</a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Estado</label>
                        <p class="mb-0">
                            @if($staff->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Rol</label>
                        <p class="mb-0">
                            @if($staff->isManager())
                                <span class="badge bg-warning text-dark">Gerente</span>
                            @else
                                <span class="badge bg-secondary">Empleado</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dirección -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Dirección</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">{{ $staff->address->address }}</p>
                    @if($staff->address->address2)
                        <p class="mb-2">{{ $staff->address->address2 }}</p>
                    @endif
                    <p class="mb-2">
                        <strong>{{ $staff->address->district }}</strong><br>
                        {{ $staff->address->city->city }}, {{ $staff->address->city->country->country }}
                    </p>
                    @if($staff->address->postal_code)
                        <p class="mb-2">CP: {{ $staff->address->postal_code }}</p>
                    @endif
                    <p class="mb-0">
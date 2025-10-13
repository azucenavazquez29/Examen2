@extends('layouts.app')

@section('title', 'Detalles del Empleado')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Empleados</a></li>
                    <li class="breadcrumb-item active">{{ $staff->full_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            @if(session('temp_password'))
                <br><strong>Contraseña temporal:</strong> <code>{{ session('temp_password') }}</code>
            @endif
            @if(session('new_password'))
                <br><strong>Nueva contraseña:</strong> <code>{{ session('new_password') }}</code>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; font-size: 2.5rem;">
                            {{ strtoupper(substr($staff->first_name, 0, 1)) }}{{ strtoupper(substr($staff->last_name, 0, 1)) }}
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $staff->full_name }}</h4>
                    <p class="text-muted mb-3">
                        @if($staff->isManager())
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-crown me-1"></i>Gerente
                            </span>
                        @else
                            <span class="badge bg-primary">Empleado</span>
                        @endif
                        @if($staff->active)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.edit', $staff->staff_id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Editar Empleado
                        </a>
                        <form action="{{ route('staff.toggle-active', $staff->staff_id) }}" method="POST">
                            @csrf
                            @if($staff->active)
                                <button type="submit" class="btn btn-outline-secondary w-100"
                                        onclick="return confirm('¿Desactivar este empleado?')">
                                    <i class="fas fa-ban me-1"></i>Desactivar
                                </button>
                            @else
                                <button type="submit" class="btn btn-outline-success w-100"
                                        onclick="return confirm('¿Activar este empleado?')">
                                    <i class="fas fa-check me-1"></i>Activar
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Rentas:</span>
                        <strong class="text-primary">{{ $stats['total_rentals'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Rentas Activas:</span>
                        <strong class="text-success">{{ $stats['active_rentals'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Rentas este mes:</span>
                        <strong class="text-info">{{ $staff->monthlyRentalsCount() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Ingresos del mes:</span>
                        <strong class="text-success">${{ number_format($staff->monthlyIncome(), 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-address-card me-2"></i>Contacto</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <strong>{{ $staff->email }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Teléfono</small>
                        <strong>{{ $staff->address->phone }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Usuario</small>
                        <code>{{ $staff->username }}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Detallada -->
        <div class="col-lg-8">
            <!-- Dirección -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Dirección</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Dirección Principal</small>
                            <strong>{{ $staff->address->address }}</strong>
                        </div>
                        @if($staff->address->address2)
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Dirección 2</small>
                            <strong>{{ $staff->address->address2 }}</strong>
                        </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Distrito</small>
                            <strong>{{ $staff->address->district }}</strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Ciudad</small>
                            <strong>{{ $staff->address->city->city ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">País</small>
                            <strong>{{ $staff->address->city->country->country ?? 'N/A' }}</strong>
                        </div>
                        @if($staff->address->postal_code)
                        <div class="col-md-4">
                            <small class="text-muted d-block">Código Postal</small>
                            <strong>{{ $staff->address->postal_code }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información de Tienda -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-store me-2"></i>Información de Tienda</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Tienda Asignada</small>
                            <strong>Tienda #{{ $staff->store->store_id }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Dirección de Tienda</small>
                            <strong>{{ $staff->store->address->address ?? 'N/A' }}</strong>
                        </div>
                        @if($staff->isManager())
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-crown me-2"></i>
                                <strong>Gerente de esta tienda</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Últimas Rentas -->
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-film me-2"></i>Últimas Rentas Procesadas</h5>
                    <span class="badge bg-primary">{{ $staff->rentals->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($staff->rentals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Renta</th>
                                    <th>Cliente</th>
                                    <th>Fecha Renta</th>
                                    <th>Fecha Devolución</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staff->rentals as $rental)
                                <tr>
                                    <td><strong>#{{ $rental->rental_id }}</strong></td>
                                    <td>{{ $rental->customer->first_name ?? 'N/A' }} {{ $rental->customer->last_name ?? '' }}</td>
                                    <td>{{ $rental->rental_date ? $rental->rental_date->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if($rental->return_date)
                                            {{ $rental->return_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rental->return_date)
                                            <span class="badge bg-success">Devuelta</span>
                                        @else
                                            <span class="badge bg-warning text-dark">En Préstamo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay rentas registradas</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <form action="{{ route('staff.reset-password', $staff->staff_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100" 
                                        onclick="return confirm('¿Resetear contraseña?')">
                                    <i class="fas fa-key me-1"></i>Resetear Contraseña
                                </button>
                            </form>
                        </div>
                        @if(!$staff->isManager())
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#changeStoreModal">
                                <i class="fas fa-exchange-alt me-1"></i>Cambiar Tienda
                            </button>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i>Eliminar Empleado
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Tienda -->
@if(!$staff->isManager())
<div class="modal fade" id="changeStoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar de Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.change-store', $staff->staff_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Seleccione la nueva tienda para <strong>{{ $staff->full_name }}</strong></p>
                    <div class="mb-3">
                        <label class="form-label">Nueva Tienda</label>
                        <select name="store_id" class="form-select" required>
                            @foreach(\App\Models\Store::all() as $store)
                                @if($store->store_id != $staff->store_id)
                                <option value="{{ $store->store_id }}">
                                    Tienda {{ $store->store_id }} - {{ $store->address->address ?? 'Sin dirección' }}
                                </option>
                                @endif
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
@endif

<!-- Modal Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar al empleado <strong>{{ $staff->full_name }}</strong>?</p>
                @if($staff->isManager())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Advertencia:</strong> Este empleado es gerente de una tienda.
                    </div>
                @endif
                @if($stats['total_rentals'] > 0)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Este empleado ha procesado <strong>{{ $stats['total_rentals'] }}</strong> rentas.
                    </div>
                @endif
                <p class="text-danger mb-0"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('staff.destroy', $staff->staff_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Empleado</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
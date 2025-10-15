@extends('layouts.app')

@section('title', 'Gestión de Empleados')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 style="color:white !important;" class="mb-0">
                    <i class="fas fa-users me-2"></i>Empleados
                </h2>
                <a href="{{ route('staff.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Nuevo Empleado
                </a>
            </div>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('staff.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tienda</label>
                    <select name="store_id" class="form-select">
                        <option value="">Todas las tiendas</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->store_id }}" 
                                {{ request('store_id') == $store->store_id ? 'selected' : '' }}>
                                Tienda {{ $store->store_id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="active" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Tienda</th>
                            <th>Estado</th>
                            <th>Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $employee)
                            <tr>
                                <td>{{ $employee->staff_id }}</td>
                                <td>
                                    <strong>{{ $employee->full_name }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $employee->address->city->city ?? 'N/A' }}, 
                                        {{ $employee->address->city->country->country ?? 'N/A' }}
                                    </small>
                                </td>
                                <td>{{ $employee->email }}</td>
                                <td><code>{{ $employee->username }}</code></td>
                                <td>
                                    <span class="badge bg-info">
                                        Tienda {{ $employee->store_id }}
                                    </span>
                                </td>
                                <td>
                                    @if($employee->active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times-circle me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($employee->isManager())
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-crown me-1"></i>Gerente
                                        </span>
                                    @else
                                        <span class="badge bg-primary">Empleado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('staff.show', $employee->staff_id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('staff.edit', $employee->staff_id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $employee->staff_id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal de confirmación -->
                                    <div class="modal fade" id="deleteModal{{ $employee->staff_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Está seguro de eliminar a <strong>{{ $employee->full_name }}</strong>?
                                                    @if($employee->isManager())
                                                        <div class="alert alert-warning mt-2">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Este empleado es gerente de una tienda.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('staff.destroy', $employee->staff_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No se encontraron empleados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Mostrando {{ $staff->firstItem() ?? 0 }} - {{ $staff->lastItem() ?? 0 }} de {{ $staff->total() }} empleados
                </div>
                <div>
                    {{ $staff->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
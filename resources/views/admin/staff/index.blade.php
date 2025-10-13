@extends('layouts.app')

@section('title', 'Gestión de Empleados')

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="fas fa-users"></i> Gestión de Empleados
            </h1>
            <p class="text-muted">Total: <strong>{{ $staff->total() }}</strong> empleados</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Empleado
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="fas fa-store"></i> Tienda
                    </label>
                    <select name="store_id" class="form-select">
                        <option value="">Todas las tiendas</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->store_id }}" 
                                {{ request('store_id') == $store->store_id ? 'selected' : '' }}>
                                Tienda {{ $store->store_id }} - {{ $store->address->city->city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i> Estado
                    </label>
                    <select name="active" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>
                            <i class="fas fa-check-circle"></i> Activos
                        </option>
                        <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>
                            <i class="fas fa-ban"></i> Inactivos
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alertas -->
    @if($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ $message }}
            @if($temp_password = session('temp_password'))
                <br><small class="mt-2 d-block"><strong>Contraseña temporal:</strong> <code>{{ $temp_password }}</code></small>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($message = session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabla de empleados -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Tienda</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $employee)
                        <tr>
                            <td>
                                <strong>{{ $employee->full_name }}</strong>
                            </td>
                            <td>
                                <code class="bg-light p-1">{{ $employee->username }}</code>
                            </td>
                            <td>
                                <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                            </td>
                            <td>
                                @if($employee->store)
                                    <span class="badge bg-info">
                                        <i class="fas fa-store"></i> Tienda {{ $employee->store->store_id }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Sin asignar</span>
                                @endif
                            </td>
                            <td>
                                @if($employee->isManager())
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-crown"></i> Gerente
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user"></i> Empleado
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($employee->active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Activo
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('staff.show', $employee->staff_id) }}" 
                                       class="btn btn-info" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('staff.edit', $employee->staff_id) }}" 
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" 
                                        onclick="confirmDelete({{ $employee->staff_id }})"
                                        title="Eliminar"
                                        {{ $employee->isManager() ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Formulario oculto para eliminar -->
                                <form id="delete-form-{{ $employee->staff_id }}" 
                                      action="{{ route('staff.destroy', $employee->staff_id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-2">No hay empleados registrados</p>
                                    <a href="{{ route('staff.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Crear Primer Empleado
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if($staff->hasPages())
        <div class="mt-4">
            {{ $staff->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Estadísticas Generales -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="text-primary">{{ $staff->total() }}</h4>
                    <p class="text-muted mb-0">Total de Empleados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="text-success">
                        {{ $staff->where('active', true)->count() }}
                    </h4>
                    <p class="text-muted mb-0">Empleados Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="text-warning">
                        {{ \App\Models\Store::count() }}
                    </h4>
                    <p class="text-muted mb-0">Tiendas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="text-info">
                        {{ $staff->whereHas('managedStore')->count() }}
                    </h4>
                    <p class="text-muted mb-0">Gerentes</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para confirmación de eliminación -->
<script>
function confirmDelete(staffId) {
    if (confirm('¿Estás seguro de que deseas eliminar este empleado? Esta acción no se puede deshacer.')) {
        document.getElementById('delete-form-' + staffId).submit();
    }
}
</script>
@endsection
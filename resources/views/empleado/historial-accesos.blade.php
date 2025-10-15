@extends('layouts.app')

@section('title', 'Historial de Accesos')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="color:white !important;" class="h3 fw-bold text-dark">Historial de Accesos de Empleados</h1>
            <p style="color:white !important;" class="text-muted mt-2">Registro completo de accesos al sistema</p>
        </div>
        <a href="{{ route('empleado.dashboard') }}" class="btn btn-secondary">
            Volver al Dashboard
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 opacity-75">Total Accesos</h6>
                    <h2 class="card-title mb-0">{{ $stats['total_accesos'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 opacity-75">Accesos Hoy</h6>
                    <h2 class="card-title mb-0">{{ $stats['accesos_hoy'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 opacity-75">Accesos Este Mes</h6>
                    <h2 class="card-title mb-0">{{ $stats['accesos_mes'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('empleado.historial-accesos') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="staff_id" class="form-label fw-medium">Empleado</label>
                        <select name="staff_id" id="staff_id" class="form-select">
                            <option value="">Todos los empleados</option>
                            @foreach($empleados as $emp)
                                <option value="{{ $emp->staff_id }}" {{ $filtroStaff == $emp->staff_id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_desde" class="form-label fw-medium">Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ $fechaDesde }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_hasta" class="form-label fw-medium">Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ $fechaHasta }}" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="w-100">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </div>
                </div>
                @if($filtroStaff || $fechaDesde || $fechaHasta)
                <div class="mt-3">
                    <a href="{{ route('empleado.historial-accesos') }}" class="btn btn-sm btn-outline-secondary">
                        Limpiar Filtros
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabla de Accesos -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted small fw-bold">ID</th>
                        <th class="text-uppercase text-muted small fw-bold">Empleado</th>
                        <th class="text-uppercase text-muted small fw-bold">Usuario</th>
                        <th class="text-uppercase text-muted small fw-bold">Fecha y Hora</th>
                        <th class="text-uppercase text-muted small fw-bold">Tipo</th>
                        <th class="text-uppercase text-muted small fw-bold">IP</th>
                        <th class="text-uppercase text-muted small fw-bold">Navegador</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accesos as $acceso)
                    <tr>
                        <td class="small">{{ $acceso->log_id }}</td>
                        <td class="small fw-medium">{{ $acceso->first_name }} {{ $acceso->last_name }}</td>
                        <td class="small text-muted">{{ $acceso->username }}</td>
                        <td class="small">{{ \Carbon\Carbon::parse($acceso->login_time)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @if($acceso->access_type == 'login')
                                <span class="badge bg-success">Login</span>
                            @else
                                <span class="badge bg-secondary">Logout</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $acceso->ip_address ?? 'N/A' }}</td>
                        <td class="small text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                            {{ $acceso->user_agent ? Str::limit($acceso->user_agent, 50) : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay registros de accesos
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($accesos->hasPages())
        <div class="border-top">
            <div class="card-body">
                {{ $accesos->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
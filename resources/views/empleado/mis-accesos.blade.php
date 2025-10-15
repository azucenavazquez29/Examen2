@extends('layouts.app')

@section('title', 'Mis Accesos')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="color:white !important;" class="h3 fw-bold text-dark">Mi Historial de Accesos</h1>
            <p style="color:white !important;" class="text-muted mt-2">Registro de todas tus sesiones en el sistema</p>
        </div>
        <a href="{{ route('empleado.dashboard') }}" class="btn btn-secondary">
            Volver al Dashboard
        </a>
    </div>

    <!-- Estadísticas Personales -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 opacity-75">Total de Accesos</h6>
                            <h2 class="card-title mb-0">{{ $stats['total_accesos'] }}</h2>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 opacity-75">Accesos Este Mes</h6>
                            <h2 class="card-title mb-0">{{ $stats['accesos_mes'] }}</h2>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 opacity-75 small">Último Acceso Anterior</h6>
                            @if($stats['ultimo_acceso'])
                                <p class="mb-0 small fw-semibold">
                                    {{ \Carbon\Carbon::parse($stats['ultimo_acceso']->login_time)->format('d/m/Y') }}
                                </p>
                                <p class="mb-0 small">
                                    {{ \Carbon\Carbon::parse($stats['ultimo_acceso']->login_time)->format('H:i:s') }}
                                </p>
                            @else
                                <p class="mb-0">Primer acceso</p>
                            @endif
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="card shadow-sm mb-4 border-start border-primary border-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <svg class="text-primary" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </svg>
                </div>
                <div class="col">
                    <h6 class="mb-1 fw-semibold">Sobre tu historial de accesos</h6>
                    <p class="mb-0 text-muted small">
                        Este registro muestra todas tus sesiones en el sistema para fines de seguridad y auditoría. 
                        Si detectas algún acceso no autorizado, contacta inmediatamente al administrador.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Accesos -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Historial Completo de Mis Accesos</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted small fw-bold">#</th>
                        <th class="text-uppercase text-muted small fw-bold">Fecha y Hora</th>
                        <th class="text-uppercase text-muted small fw-bold">Tipo de Acceso</th>
                        <th class="text-uppercase text-muted small fw-bold">Dirección IP</th>
                        <th class="text-uppercase text-muted small fw-bold">Dispositivo/Navegador</th>
                        <th class="text-uppercase text-muted small fw-bold">Hace</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accesos as $acceso)
                    <tr>
                        <td class="small text-muted">{{ $acceso->log_id }}</td>
                        <td class="small fw-medium">
                            <div>{{ \Carbon\Carbon::parse($acceso->login_time)->format('d/m/Y') }}</div>
                            <div class="text-muted">{{ \Carbon\Carbon::parse($acceso->login_time)->format('H:i:s') }}</div>
                        </td>
                        <td>
                            @if($acceso->access_type == 'login')
                                <span class="badge bg-success">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                        <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                        <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                    </svg>
                                    Inicio de sesión
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                        <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
                                        <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                                    </svg>
                                    Cierre de sesión
                                </span>
                            @endif
                        </td>
                        <td class="small">
                            <span class="badge bg-light text-dark border">
                                {{ $acceso->ip_address ?? 'No registrada' }}
                            </span>
                        </td>
                        <td class="small text-muted" style="max-width: 300px;">
                            @if($acceso->user_agent)
                                @php
                                    $ua = $acceso->user_agent;
                                    $device = 'Desconocido';
                                    $browser = '';
                                    
                                    // Detectar dispositivo
                                    if (str_contains($ua, 'Mobile')) {
                                        $device = '📱 Móvil';
                                    } elseif (str_contains($ua, 'Tablet')) {
                                        $device = '📱 Tablet';
                                    } else {
                                        $device = '💻 Escritorio';
                                    }
                                    
                                    // Detectar navegador
                                    if (str_contains($ua, 'Chrome')) {
                                        $browser = 'Chrome';
                                    } elseif (str_contains($ua, 'Firefox')) {
                                        $browser = 'Firefox';
                                    } elseif (str_contains($ua, 'Safari')) {
                                        $browser = 'Safari';
                                    } elseif (str_contains($ua, 'Edge')) {
                                        $browser = 'Edge';
                                    } elseif (str_contains($ua, 'Opera')) {
                                        $browser = 'Opera';
                                    }
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <span>{{ $device }}</span>
                                    @if($browser)
                                        <span class="badge bg-secondary bg-opacity-25 text-dark">{{ $browser }}</span>
                                    @endif
                                </div>
                                <div class="small text-truncate" style="max-width: 250px;" title="{{ $ua }}">
                                    {{ $ua }}
                                </div>
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ \Carbon\Carbon::parse($acceso->login_time)->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="text-center text-muted py-5">
                                <svg class="d-block mx-auto mb-3" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                                <h6 class="fw-semibold text-dark">No hay registros de accesos</h6>
                                <p class="small">Este es tu primer acceso al sistema</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($accesos->hasPages())
        <div class="border-top">
            <div class="card-body">
                {{ $accesos->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- Información de Seguridad -->
    <div class="alert alert-info mt-4 d-flex align-items-start" role="alert">
        <svg class="flex-shrink-0 me-2" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </svg>
        <div>
            <h6 class="alert-heading">Consejos de Seguridad</h6>
            <p class="mb-2">• Revisa regularmente tu historial de accesos para detectar actividad sospechosa</p>
            <p class="mb-2">• Cierra sesión al terminar de usar el sistema, especialmente en computadoras compartidas</p>
            <p class="mb-0">• Si observas accesos desde ubicaciones o dispositivos desconocidos, reporta inmediatamente</p>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Historial de Rentas')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="color:white !important;" class="h3 fw-bold text-dark">Historial de Rentas</h1>
            <p style="color:white !important;" class="text-muted mt-2">Cliente: {{ $cliente->first_name }} {{ $cliente->last_name }}</p>
            <p style="color:white !important;" class="text-muted small">Email: {{ $cliente->email }}</p>
        </div>
        <a href="{{ route('empleado.clientes.index') }}" class="btn btn-secondary">
            Volver a Clientes
        </a>
    </div>

    <!-- Resumen del Cliente -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Total Rentas</p>
                            <h3 class="card-title mb-0">{{ $rentas->count() }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Total Gastado</p>
                            <h3 class="card-title mb-0">${{ number_format($totalGastado, 2) }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text opacity-75 small fw-medium mb-2">Rentas Activas</p>
                            <h3 class="card-title mb-0">{{ $rentas->where('return_date', null)->count() }}</h3>
                        </div>
                        <svg class="text-white opacity-50" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Historial -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Historial Completo de Rentas</h5>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted small fw-bold">ID Renta</th>
                        <th class="text-uppercase text-muted small fw-bold">Película</th>
                        <th class="text-uppercase text-muted small fw-bold">Fecha Renta</th>
                        <th class="text-uppercase text-muted small fw-bold">Fecha Devolución</th>
                        <th class="text-uppercase text-muted small fw-bold">Duración</th>
                        <th class="text-uppercase text-muted small fw-bold">Costo</th>
                        <th class="text-uppercase text-muted small fw-bold">Cargo por Retraso</th>
                        <th class="text-uppercase text-muted small fw-bold">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentas as $renta)
                    <tr>
                        <td class="small fw-semibold text-dark">
                            #{{ $renta->rental_id }}
                        </td>
                        <td class="small text-dark">
                            {{ $renta->title }}
                            <div class="small text-muted">Tarifa: ${{ number_format($renta->rental_rate, 2) }}</div>
                        </td>
                        <td class="small text-dark">
                            {{ \Carbon\Carbon::parse($renta->rental_date)->format('d/m/Y H:i') }}
                        </td>
                        <td class="small text-dark">
                            @if($renta->return_date)
                                {{ \Carbon\Carbon::parse($renta->return_date)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-warning fw-semibold">Pendiente</span>
                            @endif
                        </td>
                        <td class="small text-dark">
                            @if($renta->return_date)
                                @php
                                    $dias = \Carbon\Carbon::parse($renta->rental_date)->diffInDays(\Carbon\Carbon::parse($renta->return_date));
                                @endphp
                                {{ $dias }} día(s)
                                @if($dias > $renta->rental_duration)
                                    <span class="text-danger fw-semibold">(+{{ $dias - $renta->rental_duration }} retraso)</span>
                                @endif
                            @else
                                @php
                                    $dias = \Carbon\Carbon::parse($renta->rental_date)->diffInDays(now());
                                @endphp
                                {{ $dias }} día(s)
                                @if($dias > $renta->rental_duration)
                                    <span class="text-danger fw-semibold">(+{{ $dias - $renta->rental_duration }} retraso)</span>
                                @endif
                            @endif
                        </td>
                        <td class="small text-dark">
                            @if($renta->amount)
                                <span class="text-success fw-semibold">${{ number_format($renta->amount, 2) }}</span>
                                <div class="small text-muted">
                                    Pagado: {{ \Carbon\Carbon::parse($renta->payment_date)->format('d/m/Y') }}
                                </div>
                            @else
                                <span class="text-muted">No pagado</span>
                            @endif
                        </td>
                        <td class="small text-dark">
                            @if($renta->dias_retraso > 0)
                                <span class="text-danger fw-semibold">${{ number_format($renta->cargo_retraso, 2) }}</span>
                                <div class="small text-muted">
                                    {{ $renta->dias_retraso }} día(s) x $1.00
                                </div>
                            @else
                                <span class="text-success">$0.00</span>
                            @endif
                        </td>
                        <td>
                            @if($renta->return_date)
                                <span class="badge bg-success">Devuelto</span>
                            @else
                                @php
                                    $diasActual = \Carbon\Carbon::parse($renta->rental_date)->diffInDays(now());
                                @endphp
                                @if($diasActual > $renta->rental_duration)
                                    <span class="badge bg-danger">Atrasado</span>
                                @else
                                    <span class="badge bg-warning text-dark">En renta</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="text-center text-muted py-5">
                                <svg class="d-block mx-auto mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h6 class="fw-semibold text-dark">Sin historial de rentas</h6>
                                <p class="small">Este cliente aún no ha rentado películas</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
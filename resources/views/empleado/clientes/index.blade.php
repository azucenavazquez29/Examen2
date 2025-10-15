@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="color:white !important;" class="h3 fw-bold text-dark">Gestión de Clientes</h1>
            <p style="color:white !important;" class="text-muted mt-2">Administra los clientes de tu sucursal</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empleado.dashboard') }}" class="btn btn-secondary">
                Volver al Dashboard
            </a>
            <a href="{{ route('empleado.clientes.crear') }}" class="btn btn-primary">
                + Registrar Nuevo Cliente
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Éxito</strong>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error</strong>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('empleado.clientes.index') }}" method="GET" class="d-flex gap-2">
                <div class="flex-grow-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Buscar por nombre, apellido o email..." 
                        class="form-control"
                    >
                </div>
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
                @if($search)
                <a href="{{ route('empleado.clientes.index') }}" class="btn btn-secondary">
                    Limpiar
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabla de Clientes -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted small fw-bold">ID</th>
                        <th class="text-uppercase text-muted small fw-bold">Nombre Completo</th>
                        <th class="text-uppercase text-muted small fw-bold">Email</th>
                        <th class="text-uppercase text-muted small fw-bold">Teléfono</th>
                        <th class="text-uppercase text-muted small fw-bold">Ciudad</th>
                        <th class="text-uppercase text-muted small fw-bold">Estado</th>
                        <th class="text-uppercase text-muted small fw-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td class="small fw-semibold text-dark">
                            {{ $cliente->customer_id }}
                        </td>
                        <td>
                            <div class="small fw-medium text-dark">
                                {{ $cliente->first_name }} {{ $cliente->last_name }}
                            </div>
                            <div class="small text-muted">
                                Desde: {{ \Carbon\Carbon::parse($cliente->create_date)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="small text-dark">
                            {{ $cliente->email }}
                        </td>
                        <td class="small text-dark">
                            {{ $cliente->phone }}
                        </td>
                        <td class="small text-muted">
                            {{ $cliente->city }}
                        </td>
                        <td>
                            @if($cliente->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="small">
                            <div class="d-flex gap-2">
                                <a href="{{ route('empleado.clientes.historial', $cliente->customer_id) }}" 
                                   class="link-primary text-decoration-none" 
                                   title="Ver historial">
                                    <svg class="text-primary" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('empleado.clientes.editar', $cliente->customer_id) }}" 
                                   class="link-warning text-decoration-none" 
                                   title="Editar">
                                    <svg class="text-warning" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="text-center text-muted py-5">
                                <svg class="d-block mx-auto mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h6 class="fw-semibold text-dark">No se encontraron clientes</h6>
                                <p class="small">
                                    {{ $search ? 'Intenta con otros términos de búsqueda' : 'Comienza registrando un nuevo cliente' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($clientes->hasPages())
        <div class="border-top">
            <div class="card-body">
                {{ $clientes->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
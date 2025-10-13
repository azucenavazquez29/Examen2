@extends('layouts.app')

@section('title', 'Cliente: ' . $customer->full_name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">{{ $customer->full_name }}</h1>
                <div class="btn-group">
                    <a href="{{ route('admin.customers.edit', $customer->customer_id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
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
                <br><small>Contraseña temporal: <code>{{ $new_password }}</code></small>
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
                        <p class="mb-0"><strong>{{ $customer->full_name }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Estado</label>
                        <p class="mb-0">
                            @if($customer->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Fecha de Registro</label>
                        <p class="mb-0">{{ $customer->create_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Dirección -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Dirección</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">{{ $customer->address->address }}</p>
                    @if($customer->address->address2)
                        <p class="mb-2">{{ $customer->address->address2 }}</p>
                    @endif
                    <p class="mb-2">
                        <strong>{{ $customer->address->district }}</strong><br>
                        {{ $customer->address->city->city }}, {{ $customer->address->city->country->country }}
                    </p>
                    @if($customer->address->postal_code)
                        <p class="mb-2">CP: {{ $customer->address->postal_code }}</p>
                    @endif
                    <p class="mb-0">
                        <i class="fas fa-phone"></i> {{ $customer->address->phone }}
                    </p>
                </div>
            </div>

            <!-- Tienda -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tienda Asignada</h5>
                </div>
                <div class="card-body">
                    @if($customer->store)
                        <p class="mb-2">
                            <strong>Tienda {{ $customer->store->store_id }}</strong>
                        </p>
                        <p class="mb-2">
                            <small class="text-muted">{{ $customer->store->address->city->city }}</small>
                        </p>
                        <a href="{{ route('stores.show', $customer->store->store_id) }}" class="btn btn-sm btn-outline-primary w-100">
                            Ver Tienda
                        </a>
                    @else
                        <p class="text-muted">Sin tienda asignada</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas y Historial -->
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
                            <h4 class="text-success">${{ number_format($stats['total_spent'], 2) }}</h4>
                            <p class="text-muted mb-0">Total Gastado</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-warning">{{ $stats['active_rentals'] }}</h4>
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

            <!-- Rentas Activas -->
            @if($stats['active_rentals'] > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Rentas Activas ({{ $stats['active_rentals'] }})</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Película</th>
                                <th>Fecha Renta</th>
                                <th>Vence en</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->activeRentals() as $rental)
                                <tr>
                                    <td>{{ $rental->inventory->film->title }}</td>
                                    <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $dueDate = $rental->rental_date->addDays($rental->inventory->film->rental_duration);
                                            $daysLeft = $dueDate->diffInDays(now());
                                        @endphp
                                        {{ $dueDate->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Vencido</span>
                                        @elseif($daysLeft <= 1)
                                            <span class="badge bg-warning">Por vencer</span>
                                        @else
                                            <span class="badge bg-success">Vigente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Historial de Rentas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historial de Rentas Recientes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Película</th>
                                <th>Renta</th>
                                <th>Devolución</th>
                                <th>Empleado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->rentalHistory(10) as $rental)
                                <tr>
                                    <td>{{ $rental->inventory->film->title }}</td>
                                    <td>{{ $rental->rental_date->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($rental->return_date)
                                            {{ $rental->return_date->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-danger">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>{{ $rental->staff->full_name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
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
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Acciones de Seguridad</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <!-- Reset Password -->
                    <form action="{{ route('admin.customers.reset-password', $customer->customer_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100"
                                onclick="return confirm('¿Resetear contraseña?')">
                            <i class="fas fa-key"></i> Resetear Contraseña
                        </button>
                    </form>

                    <!-- Toggle Active -->
                    <form action="{{ route('admin.customers.toggle-active', $customer->customer_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $customer->active ? 'btn-danger' : 'btn-success' }} w-100"
                                onclick="return confirm('¿{{ $customer->active ? 'Desactivar' : 'Activar' }} este cliente?')">
                            <i class="fas {{ $customer->active ? 'fa-ban' : 'fa-check' }}"></i>
                            {{ $customer->active ? 'Desactivar Cliente' : 'Activar Cliente' }}
                        </button>
                    </form>

                    @if($stats['active_rentals'] == 0)
                        <!-- Delete -->
                        <form action="{{ route('admin.customers.destroy', $customer->customer_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('¿Eliminar este cliente permanentemente?')">
                                <i class="fas fa-trash"></i> Eliminar Cliente
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning small">
                            <i class="fas fa-info-circle"></i> No se puede eliminar mientras tenga rentas activas
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">ID Cliente</label>
                        <p class="mb-0 small"><code>{{ $customer->customer_id }}</code></p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Registrado</label>
                        <p class="mb-0 small">{{ $customer->create_date->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Última Actualización</label>
                        <p class="mb-0 small">{{ $customer->updated_at ? $customer->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
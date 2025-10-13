<!DOCTYPE html>
<html lang="es">
    @include('parts.head')
<body>
    @include('parts.header')

    <div id="cont_principal">
        <div class="container-fluid" id="container_a">
            @include('parts.nav')

            <div class="container mt-4 mb-5">
                
                <!-- Información del Cliente -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">
                                    <i class="fas fa-user-circle"></i> 
                                    Mi Cuenta - {{ $customer->first_name }} {{ $customer->last_name }}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $customer->email ?? 'No registrado' }}</p>
                                        <p><strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong> 
                                            {{ $customer->address->address ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-city"></i> Ciudad:</strong> 
                                            {{ $customer->address->city->city ?? 'N/A' }}
                                        </p>
                                        <p><strong><i class="fas fa-phone"></i> Teléfono:</strong> 
                                            {{ $customer->address->phone ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-film fa-3x text-primary mb-2"></i>
                                <h3 class="mb-0">{{ $stats['total_rentals'] }}</h3>
                                <p class="text-muted mb-0">Total Rentas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                                <h3 class="mb-0">{{ $stats['active_rentals'] }}</h3>
                                <p class="text-muted mb-0">Rentas Activas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-dollar-sign fa-3x text-success mb-2"></i>
                                <h3 class="mb-0">${{ number_format($stats['total_paid'], 2) }}</h3>
                                <p class="text-muted mb-0">Total Pagado</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-2"></i>
                                <h3 class="mb-0">{{ $stats['overdue_rentals'] }}</h3>
                                <p class="text-muted mb-0">Rentas Vencidas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NOTIFICACIONES Y ALERTAS -->
                @if(count($notifications) > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-bell"></i> 
                                        Notificaciones ({{ count($notifications) }})
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @foreach($notifications as $notification)
                                        <div class="alert alert-{{ $notification['type'] }} alert-dismissible fade show" role="alert">
                                            <h6 class="alert-heading">
                                                <i class="fas {{ $notification['icon'] }}"></i> 
                                                {{ $notification['title'] }}
                                            </h6>
                                            <p class="mb-0">{{ $notification['message'] }}</p>
                                            @if(isset($notification['date']))
                                                <hr>
                                                <small><strong>Fecha límite:</strong> {{ $notification['date'] }}</small>
                                            @endif
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tabs de Navegación -->
                <ul class="nav nav-tabs mb-3" id="clienteTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-tab" data-bs-toggle="tab" 
                                data-bs-target="#active" type="button">
                            <i class="fas fa-clock"></i> Rentas Activas ({{ $activeRentals->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" 
                                data-bs-target="#history" type="button">
                            <i class="fas fa-history"></i> Historial de Rentas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payments-tab" data-bs-toggle="tab" 
                                data-bs-target="#payments" type="button">
                            <i class="fas fa-credit-card"></i> Pagos Realizados
                        </button>
                    </li>
                </ul>

                <!-- Contenido de los Tabs -->
                <div class="tab-content" id="clienteTabsContent">
                    
                    <!-- TAB: RENTAS ACTIVAS -->
                    <div class="tab-pane fade show active" id="active" role="tabpanel">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-play-circle"></i> 
                                    Películas que tengo actualmente
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($activeRentals->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Película</th>
                                                    <th>Fecha Renta</th>
                                                    <th>Duración</th>
                                                    <th>Días Transcurridos</th>
                                                    <th>Estado</th>
                                                    <th>Costo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($activeRentals as $rental)
                                                    @php
                                                        $film = $rental->inventory->film;
                                                        $daysRented = $rental->rental_date->diffInDays(now());
                                                        $daysAllowed = $film->rental_duration;
                                                        $daysRemaining = $daysAllowed - $daysRented;
                                                        $isOverdue = $rental->isOverdue();
                                                    @endphp
                                                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                                        <td>
                                                            <strong>{{ $film->title }}</strong><br>
                                                            <small class="text-muted">{{ $film->rating }}</small>
                                                        </td>
                                                        <td>{{ $rental->rental_date->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $daysAllowed }} días</td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $daysRented }} días</span>
                                                        </td>
                                                        <td>
                                                            @if($isOverdue)
                                                                <span class="badge bg-danger">
                                                                    <i class="fas fa-exclamation-triangle"></i>
                                                                    VENCIDA ({{ abs($daysRemaining) }} días de retraso)
                                                                </span>
                                                            @elseif($daysRemaining <= 2)
                                                                <span class="badge bg-warning text-dark">
                                                                    <i class="fas fa-clock"></i>
                                                                    Por vencer ({{ $daysRemaining }} días)
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check"></i>
                                                                    Vigente ({{ $daysRemaining }} días restantes)
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            ${{ $film->rental_rate }}
                                                            @if($isOverdue)
                                                                <br>
                                                                <small class="text-danger">
                                                                    + ${{ abs($daysRemaining) * 1.00 }} (cargo por retraso)
                                                                </small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                                        <h5>No tienes rentas activas</h5>
                                        <p>Visita nuestro <a href="{{ route('catalog') }}" class="alert-link">catálogo</a> para rentar películas.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- TAB: HISTORIAL DE RENTAS -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-history"></i> 
                                    Historial Completo de Rentas
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($rentals->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Película</th>
                                                    <th>Fecha Renta</th>
                                                    <th>Fecha Devolución</th>
                                                    <th>Duración Real</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rentals as $rental)
                                                    <tr>
                                                        <td>#{{ $rental->rental_id }}</td>
                                                        <td>
                                                            <strong>{{ $rental->inventory->film->title }}</strong>
                                                        </td>
                                                        <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                                                        <td>
                                                            @if($rental->return_date)
                                                                {{ $rental->return_date->format('d/m/Y') }}
                                                            @else
                                                                <span class="badge bg-warning">En posesión</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($rental->return_date)
                                                                {{ $rental->rental_date->diffInDays($rental->return_date) }} días
                                                            @else
                                                                {{ $rental->rental_date->diffInDays(now()) }} días (activa)
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($rental->return_date)
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check"></i> Devuelta
                                                                </span>
                                                            @elseif($rental->isOverdue())
                                                                <span class="badge bg-danger">
                                                                    <i class="fas fa-times"></i> Vencida
                                                                </span>
                                                            @else
                                                                <span class="badge bg-info">
                                                                    <i class="fas fa-clock"></i> Activa
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Paginación -->
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $rentals->appends(['payments_page' => request('payments_page')])->links() }}
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">
                                        <p>No tienes historial de rentas.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- TAB: PAGOS REALIZADOS -->
                    <div class="tab-pane fade" id="payments" role="tabpanel">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt"></i> 
                                    Historial de Pagos
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($payments->count() > 0)
                                    <!-- Resumen -->
                                    <div class="alert alert-success">
                                        <strong>Total pagado:</strong> ${{ number_format($stats['total_paid'], 2) }}
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>ID Pago</th>
                                                    <th>Fecha</th>
                                                    <th>Película</th>
                                                    <th>Monto</th>
                                                    <th>Staff</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payments as $payment)
                                                    <tr>
                                                        <td>#{{ $payment->payment_id }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            @if($payment->rental && $payment->rental->inventory)
                                                                {{ $payment->rental->inventory->film->title }}
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <strong class="text-success">
                                                                ${{ number_format($payment->amount, 2) }}
                                                            </strong>
                                                        </td>
                                                        <td>
                                                            {{ $payment->staff->first_name ?? 'N/A' }} 
                                                            {{ $payment->staff->last_name ?? '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Paginación -->
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $payments->appends(['rentals_page' => request('rentals_page')])->links() }}
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">
                                        <p>No tienes pagos registrados.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('parts.footer')
    @include('parts.scripts')
</body>
</html>
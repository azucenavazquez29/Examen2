@include('parts.head')
@include('parts.header')


<div id="cont_principal">
    <div class="container-fluid" id="container_a">
        @include('parts.nav')
        
        <h1 class="text-center my-4 display-5 fw-bold" style="color:white !important; font-weight:bolder !important;">
            <i class="fas fa-chart-line me-2"></i>Estadísticas
        </h1>

        <!-- KPI Section -->
        <div class="row mb-5">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card card-stat">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Rentas Totales</p>
                                <p class="stat-value">{{ number_format($totalRentals) }}</p>
                            </div>
                            <div class="stat-icon" style="background: rgba(99, 102, 241, 0.3); color: #6366f1;">
                                <i class="fas fa-video"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card card-stat">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Ingresos Totales</p>
                                <p class="stat-value">${{ number_format($totalRevenue, 2) }}</p>
                            </div>
                            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.3); color: #10b981;">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card card-stat">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Películas Rentadas</p>
                                <p class="stat-value">{{ number_format($uniqueFilmsRented) }}</p>
                            </div>
                            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.3); color: #f59e0b;">
                                <i class="fas fa-film"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card card-stat">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Clientes Activos</p>
                                <p class="stat-value">{{ number_format($activeCustomers) }}</p>
                            </div>
                            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.3); color: #3b82f6;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5 class="mb-4"><i class="fas fa-filter me-2"></i>Filtros</h5>
            <form method="GET" action="{{ route('stats') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-600">Sucursal</label>
                        <select class="form-select form-select-dark" name="store_id">
                            <option value="">Todas las sucursales</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->store_id }}" {{ request('store_id') == $store->store_id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Aplicar filtros
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabs for Different Reports -->
        <div class="mb-5">
            <ul class="nav tabs-custom mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-overview" data-bs-toggle="tab" data-bs-target="#overview" type="button">
                        <i class="fas fa-chart-pie me-2"></i>Resumen
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-store" data-bs-toggle="tab" data-bs-target="#store" type="button">
                        <i class="fas fa-store me-2"></i>Por Sucursal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-ranking" data-bs-toggle="tab" data-bs-target="#ranking" type="button">
                        <i class="fas fa-trophy me-2"></i>Rankings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-category" data-bs-toggle="tab" data-bs-target="#category" type="button">
                        <i class="fas fa-tags me-2"></i>Por Categoría
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h6 class="mb-4">Rentas por Sucursal</h6>
                                <canvas id="chartStoreRentals"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h6 class="mb-4">Ingresos por Sucursal</h6>
                                <canvas id="chartStoreIncome"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="chart-container">
                                <h6 class="mb-4">Tendencia de Ingresos (Últimos 12 meses)</h6>
                                <canvas id="chartTrend"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Tab -->
                <div class="tab-pane fade" id="store" role="tabpanel">
                    <div class="table-custom">
                        <table class="table table-hover mb-0" style="color:black !important;">
                            <thead>
                                <tr>
                                    <th style="color:black !important;">Sucursal</th>
                                    <th style="color:black !important;">Gerente</th>
                                    <th style="color:black !important;">Rentas</th>
                                    <th style="color:black !important;">Ingresos</th>
                                    <th style="color:black !important;">Ticket Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storeStats as $store)
                                <tr>
                                    <td style="color:black !important;"><strong>{{ $store->store_name }}</strong></td>
                                    <td style="color:black !important;">{{ $store->manager ?? 'N/A' }}</td>
                                    <td style="color:black !important;"><span class="badge badge-custom">{{ number_format($store->total_rentals) }}</span></td>
                                    <td style="color:black !important;"><strong>${{ number_format($store->total_revenue, 2) }}</strong></td>
                                    <td style="color:black !important;">${{ number_format($store->avg_ticket, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No hay datos disponibles para el período seleccionado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ranking Tab -->
                <div class="tab-pane fade" id="ranking" role="tabpanel">
                    <div class="row">
                        <!-- Películas más rentadas -->
                        <div class="col-lg-6 mb-4">
                            <div class="table-custom">
                                <div class="p-4 border-bottom" style="border-color: #404854 !important;">
                                    <h6 class="mb-0"><i class="fas fa-star me-2 text-warning"></i>Películas Más Rentadas</h6>
                                </div>
                                @forelse($topFilms as $index => $film)
                                <div class="ranking-item">
                                    <div class="ranking-position">
                                        @if($index == 0)
                                            <span class="ranking-medal">🥇</span>
                                        @elseif($index == 1)
                                            <span class="ranking-medal">🥈</span>
                                        @elseif($index == 2)
                                            <span class="ranking-medal">🥉</span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <strong>{{ $film->title }}</strong>
                                        <br><small class="text-muted">{{ number_format($film->rental_count) }} rentas</small>
                                    </div>
                                </div>
                                @empty
                                <div class="p-4 text-center text-muted">
                                    No hay datos disponibles
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Clientes con más rentas -->
                        <div class="col-lg-6 mb-4">
                            <div class="table-custom">
                                <div class="p-4 border-bottom" style="border-color: #404854 !important;">
                                    <h6 class="mb-0"><i class="fas fa-users me-2 text-info"></i>Clientes con Más Rentas</h6>
                                </div>
                                @forelse($topCustomers as $index => $customer)
                                <div class="ranking-item">
                                    <div class="ranking-position">
                                        @if($index == 0)
                                            <span class="ranking-medal">🥇</span>
                                        @elseif($index == 1)
                                            <span class="ranking-medal">🥈</span>
                                        @elseif($index == 2)
                                            <span class="ranking-medal">🥉</span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <strong>{{ $customer->name }}</strong>
                                        <br><small class="text-muted">{{ number_format($customer->rental_count) }} rentas | ${{ number_format($customer->total_spent, 2) }}</small>
                                    </div>
                                    <div class="text-end">
                                        @if($customer->rental_count >= 30)
                                            <span class="badge bg-primary">VIP</span>
                                        @else
                                            <span class="badge bg-info">REGULAR</span>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div class="p-4 text-center text-muted">
                                    No hay datos disponibles
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Tab -->
                <div class="tab-pane fade" id="category" role="tabpanel">
                    <div class="chart-container">
                        <h6 class="mb-4">Rentas por Categoría</h6>
                        <canvas id="chartCategory"></canvas>
                    </div>
                    <div class="table-custom mt-4">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr >
                                    <th style="color:black !important;">Categoría</th>
                                    <th style="color:black !important;">Rentas</th>
                                    <th style="color:black !important;">Ingresos</th>
                                    <th style="color:black !important;">Ticket Promedio</th>
                                    <th style="color:black !important;">Películas</th>
                                    <th style="color:black !important;">Inventario Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxInventory = $categoryStats->max('inventory_count') ?: 1;
                                @endphp
                                @forelse($categoryStats as $category)
                                <tr>
                                    <td style="color:black !important;"><strong>{{ $category->name }}</strong></td>
                                    <td style="color:black !important;">{{ number_format($category->total_rentals) }}</td>
                                    <td style="color:black !important;"><strong>${{ number_format($category->total_revenue, 2) }}</strong></td>
                                    <td style="color:black !important;">${{ number_format($category->avg_ticket, 2) }}</td>
                                    <td style="color:black !important;">{{ number_format($category->film_count) }}</td>
                                    <td style="color:black !important;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar progress-bar-custom" 
                                                     style="width: {{ ($category->inventory_count / $maxInventory * 100) }}%;"></div>
                                            </div>
                                            <span class="ms-2 text-muted" style="font-size: 12px;">{{ $category->inventory_count }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No hay datos disponibles para el período seleccionado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #6366f1;
        --secondary: #8b5cf6;
        --danger: #ef4444;
        --success: #10b981;
        --warning: #f59e0b;
        --info: #3b82f6;
        --dark-bg: #1a1d23;
        --dark-card: #262d38;
        --dark-border: #404854;
    }

    #cont_principal {
        background: var(--dark-bg);
        color: #e0e0e0;
        min-height: 100vh;
    }

    .card-stat {
        background: var(--dark-card) !important;
        border: 1px solid var(--dark-border) !important;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        border-color: var(--primary) !important;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: bold;
        color: #6366f1;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 14px;
        color: #a0a0a0;
        font-weight: 500;
    }

    .chart-container {
        background: var(--dark-card) !important;
        border: 1px solid var(--dark-border) !important;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        margin-bottom: 25px;
    }

    .chart-container h6 {
        color: #e0e0e0;
        font-weight: 600;
    }

    .table-custom {
        background: var(--dark-card) !important;
        border: 1px solid var(--dark-border) !important;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .table-custom thead {
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        color: white;
    }

    .table-custom tbody {
        background: var(--dark-card);
        color: #e0e0e0;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid var(--dark-border);
        transition: background 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background: rgba(99, 102, 241, 0.1);
    }

    .table-custom td, .table-custom th {
        color: #e0e0e0;
        border-color: var(--dark-border);
        padding: 15px;
    }

    .badge-custom {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(99, 102, 241, 0.2);
        color: #6366f1;
    }

    .filter-section {
        background: var(--dark-card) !important;
        border: 1px solid var(--dark-border) !important;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .filter-section h5 {
        color: #e0e0e0;
        font-weight: 600;
    }

    .form-select-dark {
        background-color: var(--dark-card) !important;
        border-color: var(--dark-border) !important;
        color: #e0e0e0 !important;
        border-radius: 8px;
        padding: 10px 15px;
    }

    .form-select-dark:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    .form-select-dark option {
        background: var(--dark-card);
        color: #e0e0e0;
    }

    .form-label {
        color: #e0e0e0;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .tabs-custom {
        border-bottom: 2px solid var(--dark-border);
        margin-bottom: 0 !important;
    }

    .tabs-custom .nav-link {
        color: #a0a0a0;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 600;
        padding: 12px 20px;
        background: var(--dark-card);
        margin-right: 5px;
        margin-bottom: -2px;
        border-radius: 8px 8px 0 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .tabs-custom .nav-link:hover {
        color: var(--primary);
        background: rgba(99, 102, 241, 0.1);
        border-bottom-color: transparent;
    }

    .tabs-custom .nav-link.active {
        color: white !important;
        background: linear-gradient(90deg, var(--primary), var(--secondary)) !important;
        border-bottom-color: var(--secondary) !important;
    }

    .tab-content {
        background: transparent;
        padding-top: 20px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .ranking-item {
        padding: 15px 20px;
        border-bottom: 1px solid var(--dark-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--dark-card);
        color: #e0e0e0;
        transition: background 0.2s ease;
    }

    .ranking-item:hover {
        background: rgba(99, 102, 241, 0.05);
    }

    .ranking-item:last-child {
        border-bottom: none;
    }

    .ranking-position {
        font-size: 20px;
        font-weight: bold;
        color: var(--primary);
        width: 50px;
        text-align: center;
        flex-shrink: 0;
    }

    .ranking-medal {
        font-size: 24px;
    }

    .progress {
        background-color: rgba(99, 102, 241, 0.1);
        border-radius: 10px;
    }

    .progress-bar-custom {
        background: linear-gradient(90deg, var(--primary), var(--secondary)) !important;
    }

    .text-muted {
        color: #7a7a7a !important;
    }

    .btn-primary {
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
    }

    .badge.bg-primary {
        background: var(--primary) !important;
    }

    .badge.bg-info {
        background: var(--info) !important;
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    // Configuración de Chart.js para tema oscuro
    Chart.defaults.color = '#a0a0a0';
    Chart.defaults.borderColor = '#404854';
    Chart.defaults.backgroundColor = 'rgba(99, 102, 241, 0.1)';

    // Datos desde el backend
    const rentalsByStore = @json($rentalsByStore);
    const revenueByStore = @json($revenueByStore);
    const revenueTrend = @json($revenueTrend);
    const rentalsByCategory = @json($rentalsByCategory);

    // Chart 1: Rentas por Sucursal (Bar)
    const chartCtx1 = document.getElementById('chartStoreRentals').getContext('2d');
    new Chart(chartCtx1, {
        type: 'bar',
        data: {
            labels: rentalsByStore.map(s => s.store_name),
            datasets: [{
                label: 'Rentas',
                data: rentalsByStore.map(s => s.total),
                backgroundColor: ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd'],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#262d38',
                    titleColor: '#e0e0e0',
                    bodyColor: '#e0e0e0',
                    borderColor: '#404854',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#a0a0a0' },
                    grid: { color: '#404854' }
                },
                x: {
                    ticks: { color: '#a0a0a0' },
                    grid: { display: false }
                }
            }
        }
    });

    // Chart 2: Ingresos por Sucursal (Doughnut)
    const chartCtx2 = document.getElementById('chartStoreIncome').getContext('2d');
    new Chart(chartCtx2, {
        type: 'doughnut',
        data: {
            labels: revenueByStore.map(s => s.store_name),
            datasets: [{
                data: revenueByStore.map(s => parseFloat(s.total)),
                backgroundColor: ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd'],
                borderColor: '#262d38',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: { 
                        color: '#a0a0a0',
                        padding: 15,
                        font: { size: 12 }
                    },
                    position: 'bottom'
                },
                tooltip: {
                    backgroundColor: '#262d38',
                    titleColor: '#e0e0e0',
                    bodyColor: '#e0e0e0',
                    borderColor: '#404854',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': $' + context.parsed.toFixed(2);
                        }
                    }
                }
            }
        }
    });

    // Chart 3: Tendencia de Ingresos (Line)
    const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    const chartCtx3 = document.getElementById('chartTrend').getContext('2d');
    new Chart(chartCtx3, {
        type: 'line',
        data: {
            labels: revenueTrend.map(r => {
                const [year, month] = r.month.split('-');
                return monthNames[parseInt(month) - 1] + ' ' + year;
            }),
            datasets: [{
                label: 'Ingresos',
                data: revenueTrend.map(r => parseFloat(r.total)),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#8b5cf6',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: { color: '#a0a0a0' }
                },
                tooltip: {
                    backgroundColor: '#262d38',
                    titleColor: '#e0e0e0',
                    bodyColor: '#e0e0e0',
                    borderColor: '#404854',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return 'Ingresos: $' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        color: '#a0a0a0',
                        callback: function(value) {
                            return '$' + value.toFixed(0);
                        }
                    },
                    grid: { color: '#404854' }
                },
                x: {
                    ticks: { color: '#a0a0a0' },
                    grid: { color: '#404854' }
                }
            }
        }
    });

    // Chart 4: Rentas por Categoría (Horizontal Bar)
    const chartCtx4 = document.getElementById('chartCategory').getContext('2d');
    new Chart(chartCtx4, {
        type: 'bar',
        data: {
            labels: rentalsByCategory.map(c => c.name),
            datasets: [{
                label: 'Rentas',
                data: rentalsByCategory.map(c => c.total),
                backgroundColor: '#6366f1',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#262d38',
                    titleColor: '#e0e0e0',
                    bodyColor: '#e0e0e0',
                    borderColor: '#404854',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { color: '#a0a0a0' },
                    grid: { color: '#404854' }
                },
                y: {
                    ticks: { color: '#a0a0a0' },
                    grid: { display: false }
                }
            }
        }
    });
</script>

<!-- Script para inicializar las tabs manualmente si Bootstrap no carga -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si Bootstrap está cargado
        if (typeof bootstrap === 'undefined') {
            console.log('Activando tabs manualmente...');
            
            // Implementación manual de tabs
            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remover active de todos los botones
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.setAttribute('aria-selected', 'false');
                    });
                    
                    // Remover active de todos los panes
                    tabPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });
                    
                    // Activar el botón clickeado
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                    
                    // Activar el pane correspondiente
                    const targetId = this.getAttribute('data-bs-target');
                    const targetPane = document.querySelector(targetId);
                    if (targetPane) {
                        targetPane.classList.add('show', 'active');
                    }
                });
            });
        } else {
            console.log('Bootstrap tabs cargado correctamente');
        }
    });
</script>

@include('parts.footer')


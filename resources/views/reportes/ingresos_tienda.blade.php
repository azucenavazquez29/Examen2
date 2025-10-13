@extends('layouts.app')

@section('title', 'Ingresos por tienda')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Ingresos por tienda</h2>

    <div class="mb-3">
        <a href="{{ route('reportes.export.csv', 'ingresos_tienda') }}" class="btn btn-success">Exportar CSV</a>
        <a href="{{ route('reportes.export.pdf', 'ingresos_tienda') }}" class="btn btn-danger">Exportar PDF</a>
    </div>

    <!-- Gráfica de ingresos -->
    <div class="mb-4">
        <canvas id="ingresosChart" height="100"></canvas>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Tienda</th>
                <th>Ubicación</th>
                <th>Encargado</th>
                <th>Total Ingresos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->store_id }}</td>
                <td>{{ $row->ubicacion }}</td>
                <td>{{ $row->manager }}</td>
                <td>${{ number_format($row->total_ingresos, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('ingresosChart').getContext('2d');

const ingresosChart = new Chart(ctx, {
    type: 'bar', // Puede ser 'line', 'pie', etc.
    data: {
        labels: @json($data->pluck('ubicacion')), // Ubicaciones de las tiendas
        datasets: [{
            label: 'Total Ingresos',
            data: @json($data->pluck('total_ingresos')),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            title: { display: true, text: 'Ingresos por tienda' }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection

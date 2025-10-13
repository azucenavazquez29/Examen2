@extends('layouts.app')

@section('title', 'Rentas por Sucursal')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Rentas por Sucursal</h2>

    <div class="mb-3">
        <a href="{{ route('reportes.export.csv', 'rentas_sucursal') }}" class="btn btn-success">Exportar CSV</a>
        <a href="{{ route('reportes.export.pdf', 'rentas_sucursal') }}" class="btn btn-danger">Exportar PDF</a>
    </div>

    <canvas id="chartSucursal" height="100"></canvas>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID Tienda</th>
                <th>Ubicación</th>
                <th>Total Rentas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->store_id }}</td>
                <td>{{ $row->ubicacion }}</td>
                <td>{{ $row->total_rentas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxSucursal = document.getElementById('chartSucursal').getContext('2d');
    new Chart(ctxSucursal, {
        type: 'bar',
        data: {
            labels: @json($data->pluck('ubicacion')),
            datasets: [{
                label: 'Total Rentas',
                data: @json($data->pluck('total_rentas')),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection

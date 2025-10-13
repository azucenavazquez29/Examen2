@extends('layouts.app')

@section('title', 'Clientes con más Rentas')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Clientes con más Rentas</h2>

    <div class="mb-3">
        <a href="{{ route('reportes.export.csv', 'clientes_top') }}" class="btn btn-success">Exportar CSV</a>
        <a href="{{ route('reportes.export.pdf', 'clientes_top') }}" class="btn btn-danger">Exportar PDF</a>
    </div>

    <canvas id="chartClientes" height="100"></canvas>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Total Rentas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->customer_id }}</td>
                <td>{{ $row->cliente }}</td>
                <td>{{ $row->email }}</td>
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
    const ctxClientes = document.getElementById('chartClientes').getContext('2d');
    new Chart(ctxClientes, {
        type: 'bar',
        data: {
            labels: @json($data->pluck('cliente')),
            datasets: [{
                label: 'Total Rentas',
                data: @json($data->pluck('total_rentas')),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection

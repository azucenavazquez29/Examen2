@extends('layouts.app')

@section('title', 'Rentas por Categoría')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Rentas por Categoría</h2>

    <div class="mb-3">
        <a href="{{ route('reportes.export.csv', 'rentas_categoria') }}" class="btn btn-success">Exportar CSV</a>
        <a href="{{ route('reportes.export.pdf', 'rentas_categoria') }}" class="btn btn-danger">Exportar PDF</a>
    </div>

    <canvas id="chartCategoria" height="100"></canvas>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Categoría</th>
                <th>Total Rentas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->categoria }}</td>
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
    const ctxCategoria = document.getElementById('chartCategoria').getContext('2d');
    new Chart(ctxCategoria, {
        type: 'pie',
        data: {
            labels: @json($data->pluck('categoria')),
            datasets: [{
                data: @json($data->pluck('total_rentas')),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)'
                ],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection

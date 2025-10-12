@extends('layouts.app')

@section('title', 'Rentas por Actor')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Rentas por Actor</h2>

    <div class="mb-3">
        <a href="{{ route('reportes.export.csv', 'rentas_actor') }}" class="btn btn-success">Exportar CSV</a>
        <a href="{{ route('reportes.export.pdf', 'rentas_actor') }}" class="btn btn-danger">Exportar PDF</a>
    </div>

    <canvas id="chartActor" height="100"></canvas>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Actor</th>
                <th>Total Rentas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->actor }}</td>
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
    const ctxActor = document.getElementById('chartActor').getContext('2d');
    new Chart(ctxActor, {
        type: 'bar',
        data: {
            labels: @json($data->pluck('actor')),
            datasets: [{
                label: 'Total Rentas',
                data: @json($data->pluck('total_rentas')),
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection

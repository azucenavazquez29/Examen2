@extends('layouts.app')

@section('title', 'Películas más rentadas')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"> Películas más rentadas</h2>

    <div class="mb-3">
        <a href="{{ route('reportes.export.csv', 'peliculas_top') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
        </a>
        <a href="{{ route('reportes.export.pdf', 'peliculas_top') }}" class="btn btn-danger">
            <i class="bi bi-filetype-pdf"></i> Exportar PDF
        </a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Veces Rentada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->film_id }}</td>
                <td>{{ $row->title }}</td>
                <td>{{ $row->veces_rentada }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

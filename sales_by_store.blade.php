@extends('layouts.app')

@section('content')
<h2>Sales By Category</h2>
<a href="{{ url('/reportes/sales-category/csv') }}" class="btn btn-success">CSV</a>
<a href="{{ url('/reportes/sales-category/pdf') }}" class="btn btn-danger">PDF</a>

<table border="1" cellpadding="5">
    <tr>
        <th>Category</th>
        <th>Total Sales</th>
    </tr>
    @foreach($data as $row)
    <tr>
        <td>{{ $row->category }}</td>
        <td>{{ $row->total_sales }}</td>
    </tr>
    @endforeach
</table>
@endsection

@extends('layouts.app')

@section('content')
<h2>Sales By Store</h2>
<a href="{{ url('/reportes/sales-store/csv') }}" class="btn btn-success">CSV</a>
<a href="{{ url('/reportes/sales-store/pdf') }}" class="btn btn-danger">PDF</a>

<table border="1" cellpadding="5">
    <tr>
        <th>Store</th>
        <th>Manager</th>
        <th>Total Sales</th>
    </tr>
    @foreach($data as $row)
    <tr>
        <td>{{ $row->store }}</td>
        <td>{{ $row->manager }}</td>
        <td>{{ $row->total_sales }}</td>
    </tr>
    @endforeach
</table>
@endsection

@include('reportes.pdf._header', ['title' => 'Ingresos por Sucursal'])
<table>
<thead><tr><th>Sucursal</th><th>Ubicación</th><th>Ingresos Totales</th></tr></thead><tbody>
@foreach($data as $r)
<tr><td>#{{ $r->store_id }}</td><td>{{ $r->location }}</td><td>${{ number_format($r->total_income, 2) }}</td></tr>
@endforeach
<tr class="total"><td colspan="2">TOTAL</td><td>${{ number_format($total, 2) }}</td></tr>
</tbody></table>
@include('reportes.pdf._footer')

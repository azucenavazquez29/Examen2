@include('reportes.pdf._header', ['title' => 'Rentas por Sucursal'])
<table>
<thead>
<tr><th>Sucursal</th><th>Gerente</th><th>Total Ventas</th></tr>
</thead><tbody>
@foreach($data as $r)
<tr><td>{{ $r->store }}</td><td>{{ $r->manager }}</td><td>${{ number_format($r->total_sales, 2) }}</td></tr>
@endforeach
<tr class="total"><td colspan="2">TOTAL</td><td>${{ number_format($total, 2) }}</td></tr>
</tbody></table>
@include('reportes.pdf._footer')

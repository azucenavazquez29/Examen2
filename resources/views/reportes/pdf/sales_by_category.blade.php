@include('reportes.pdf._header', ['title' => 'Rentas por Categoría'])
<table>
<thead><tr><th>Categoría</th><th>Total Rentas</th><th>Total Ingresos</th></tr></thead><tbody>
@foreach($data as $r)
<tr><td>{{ $r->category }}</td><td>{{ $r->total_rentals }}</td><td>${{ number_format($r->total_income, 2) }}</td></tr>
@endforeach
<tr class="total"><td colspan="2">TOTAL</td><td>${{ number_format($total, 2) }}</td></tr>
</tbody></table>
@include('reportes.pdf._footer')

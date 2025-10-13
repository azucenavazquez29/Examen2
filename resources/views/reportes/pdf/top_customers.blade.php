@include('reportes.pdf._header', ['title' => 'Top 10 Clientes'])
<table>
<thead><tr><th>Cliente</th><th>Rentas</th><th>Total Gastado</th></tr></thead><tbody>
@foreach($data as $r)
<tr><td>{{ $r->customer }}</td><td>{{ $r->total_rentals }}</td><td>${{ number_format($r->total_spent, 2) }}</td></tr>
@endforeach
<tr class="total"><td colspan="2">TOTAL</td><td>${{ number_format($total, 2) }}</td></tr>
</tbody></table>
@include('reportes.pdf._footer')

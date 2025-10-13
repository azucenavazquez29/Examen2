@include('reportes.pdf._header', ['title' => 'Top 10 Películas Más Rentadas'])
<table>
<thead><tr><th>Película</th><th>Rentas</th><th>Ingresos</th></tr></thead><tbody>
@foreach($data as $r)
<tr><td>{{ $r->movie }}</td><td>{{ $r->total_rentals }}</td><td>${{ number_format($r->total_income, 2) }}</td></tr>
@endforeach
</tbody></table>
@include('reportes.pdf._footer')

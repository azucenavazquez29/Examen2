<!DOCTYPE html>


<html lang="es">

        @include('parts.head')

<body>

@include('parts.header')


        <div id="cont_principal">
            <div class="container-fluid" id="container_a">
                
            @include('parts.nav')



            <section class="contenedor_inicio">


                <section class="music_cards">
                     
                <div class="contorno_titulo_cartas"><span>Rentar Peliculas a Clientes</span></div>


                        <div class="cuadricula_contenido">
                            @foreach ($films_tst as $film)
                                <div class="seccion_artista">
                                    <div class="contenedor_albumes edit_artistas" style="border-radius:0.4rem;">
                                        <div class="animacion_carta" style="border-radius:0.4rem;">
                                            <a href="#">
                                                <img width="100%" src="images/imagen_alargada2.jpg" alt="Album Nuevo">
                                                <div class="efecto_imagen">
                                                    <svg width="60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="marca_album marca_artista"><span>{{ $film->title }}</span></div>
                                                </div>
                                                <div class="marca_formato formato_artista"><span>{{ $film->rental_rate }}</span></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div style="margin:10px;"><span>{{ $film->title }}</span></div>

                                    <div class="efecto_imagen_artista">
                                        <div class="">
                                            <div class="letra_artista"><span style="font-size: 1.5rem;">Costo: {{ $film->replacement_cost }}</span></div>
                                            <div class="letra_artista"><span style="font-size: 1.5rem;">Rating: {{ $film->rating }}</span></div>
                                            <div class="letra_artista"><span style="font-size: 1.5rem;">Duración Renta: {{ $film->rental_duration }}</span></div>
                                            <div><span style="font-size:1.7rem; font-weight:bold; margin:15px;">{{ $film->title }}</span></div>
                                            <div class="letra_parrafo_artista"><p>{{ Str::limit($film->description, 100) }}</p></div>

                                            <!-- INFORMACIÓN DE INVENTARIO -->
                                            <div class="mb-3">
                                                <div class="row text-center" style="font-weight:bolder; color:black; font-size:1.2rem;">
                                                    <div class="col-6">
                                                        <div class="bg-light p-2 rounded">
                                                            <strong>{{ $film->availableCopies() }}</strong><br>
                                                            <small>Disponibles</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-light p-2 rounded">
                                                            <strong>{{ $film->totalCopies() }}</strong><br>
                                                            <small>Total Copias</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Estado y botón -->
                                            @if($film->isAvailable())
                                                <div style="text-align:center;"><span style="padding:1rem;font-size:0.7rem;" class="badge bg-success mb-2">✅ Disponible ({{ $film->availableCopies() }} copias)</span></div>
                                            @else
                                                <div style="text-align:center;"><span style="padding:1rem;font-size:0.7rem;" class="badge bg-danger mb-2">🚫 Todas rentadas ({{ $film->activeRentals()->count() }} rentas activas)</span></div>
                                            @endif

                                            <!-- Botón que abre modal -->
                                            <button class="btn w-100 {{ $film->isAvailable() ? 'btn-primary' : 'btn-warning' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reserveModal{{ $film->film_id }}">
                                                @if($film->isAvailable())
                                                    <svg width="1rem" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                                    </svg>
                                                    @if($film->activeRentals()->count() > 0)
                                                        Rentar y Gestionar ({{ $film->activeRentals()->count() }} activas)
                                                    @else
                                                        Reservar Película
                                                    @endif
                                                @else
                                                    <svg width="1rem" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                                    </svg>
                                                    Gestionar Rentas ({{ $film->activeRentals()->count() }})
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal mejorado -->
                                <div class="modal fade" id="reserveModal{{ $film->film_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            
                                            @if($film->isAvailable())
                                                {{-- ✅ Caso: disponible - Mostrar formulario de renta Y tabla de devoluciones --}}
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $film->title }} - Reservar y Gestionar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Info de inventario -->
                                                    <div class="alert alert-success">
                                                        <strong>Estado del inventario:</strong><br>
                                                        Copias disponibles: {{ $film->availableCopies() }} de {{ $film->totalCopies() }}
                                                    </div>

                                                    <!-- Sección de nueva renta -->
                                                    <div class="card mb-4">
                                                        <div class="card-header bg-primary text-white">
                                                            <h6 class="mb-0">Nueva Renta</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <form action="{{ route('rent.store') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="film_id" value="{{ $film->film_id }}">
                                                                
                            <div class="mb-3">
                                <label for="customer_id{{ $film->film_id }}" class="form-label">Seleccionar Cliente</label>
                                <select name="customer_id" id="customer_id{{ $film->film_id }}" class="form-select" required>
                                    <option value="">Seleccione un cliente...</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->customer_id }}"
                                            {{ $customer->hasOverdueRentals() ? 'disabled' : '' }}>
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                            @if($customer->email)
                                                ({{ $customer->email }})
                                            @endif
                                            @if($customer->hasOverdueRentals())
                                                ⚠️ - TIENE RENTAS ATRASADAS
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Los clientes con películas atrasadas no pueden realizar nuevas rentas</small>
                            </div>

                                                                <div class="alert alert-info">
                                                                    <strong>Detalles de la renta:</strong><br>
                                                                    Precio: ${{ $film->rental_rate }}<br>
                                                                    Duración: {{ $film->rental_duration }} días<br>
                                                                    Costo de reemplazo: ${{ $film->replacement_cost }}
                                                                </div>

                                                                <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <!-- Sección de rentas activas (siempre visible si hay rentas) -->
                                                    @if($film->activeRentals()->count() > 0)
                                                        <div class="card">
                                                            <div class="card-header bg-warning text-dark">
                                                                <h6 class="mb-0">Rentas Activas ({{ $film->activeRentals()->count() }})</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Cliente</th>
                                                                                <th>Email</th>
                                                                                <th>Fecha Renta</th>
                                                                                <th>Días</th>
                                                                                <th>Acción</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($film->activeRentals() as $rental)
                                                                                <tr>
                                                                                    <td>{{ $rental->customer->first_name }} {{ $rental->customer->last_name }}</td>
                                                                                    <td>{{ $rental->customer->email ?? 'N/A' }}</td>
                                                                                    <td>{{ $rental->rental_date->format('d/m/Y H:i') }}</td>
                                                                                    <td>
                                                                                        <span class="badge {{ $rental->isOverdue() ? 'bg-danger' : 'bg-info' }}">
                                                                                            {{ $rental->rental_date->diffInDays(now()) }} días
                                                                                            @if($rental->isOverdue()) - VENCIDO @endif
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <form action="{{ route('rent.return', $rental->rental_id) }}" method="POST" style="display: inline;">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <button type="submit" class="btn btn-warning btn-sm">
                                                                                                Devolver
                                                                                            </button>
                                                                                        </form>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            
                                            @else
                                                {{-- 🚫 Caso: todas las copias rentadas --}}
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $film->title }} - Todas las copias rentadas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-danger">
                                                        <strong>No hay copias disponibles</strong><br>
                                                        Total de copias: {{ $film->totalCopies() }}<br>
                                                        Todas están actualmente rentadas
                                                    </div>

                                                    <h6>Lista de rentas activas:</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Cliente</th>
                                                                    <th>Email</th>
                                                                    <th>Fecha Renta</th>
                                                                    <th>Días</th>
                                                                    <th>Acción</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($film->activeRentals() as $rental)
                                                                    <tr>
                                                                        <td>{{ $rental->customer->first_name }} {{ $rental->customer->last_name }}</td>
                                                                        <td>{{ $rental->customer->email ?? 'N/A' }}</td>
                                                                        <td>{{ $rental->rental_date->format('d/m/Y H:i') }}</td>
                                                                        <td>
                                                                            <span class="badge {{ $rental->isOverdue() ? 'bg-danger' : 'bg-info' }}">
                                                                                {{ $rental->rental_date->diffInDays(now()) }} días
                                                                                @if($rental->isOverdue()) - VENCIDO @endif
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <form action="{{ route('rent.return', $rental->rental_id) }}" method="POST" style="display: inline;">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <button type="submit" class="btn btn-warning btn-sm">
                                                                                    Procesar Devolución
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="container mt-4 mb-5">
                            <div class="d-flex justify-content-center">
                                <div class="pagination-wrapper">
                                    {{ $films_tst->links() }}
                                </div>
                            </div>
                        </div>

                        
                        <div class="contorno_titulo_cartas"><span>Anuncios</span></div>
                        <div class="cuadricula_anuncios">
                            <?php for($i=0;$i<3;$i++): ?>
                                <div class="targeta_anuncio">
                                    <div class="info_fecha_anuncio">
                                        <div class="numero_anuncio">10</div>
                                        <div class="mes_anuncio">SEPTIEMBRE</div>
                                    </div>
                                   
                                    <div class="info_anuncio">
                                        <div class="titulo_anuncio">Cómo saltar el bloqueo de proveedor de internet en Perukistán</div>
                                        <div class="texto_anuncio">Algunos usuarios me han ido notificando que la web aparece como caída o que directamente no funciona pero cuando usan una VPN si funciona sin problema. Esto parece ser causado...</div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                </section>
                
            </section>

        </div>
    </div>



    @include('parts.footer')
    @include('parts.scripts')
    

</body>


</html>
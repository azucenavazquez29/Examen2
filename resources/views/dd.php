    <div class="cuadricula_contenido row">
            @foreach ($films_tst as $film)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="seccion_artista">
                            <div class="contenedor_albumes edit_artistas" style="border-radius:0.4rem;">
                                <div class="animacion_carta" style="border-radius:0.4rem;">
                                    <a href="#">
                                        <img class="card-img-top" src="images/imagen_alargada2.jpg" alt="{{ $film->title }}" style="height: 200px; object-fit: cover;">
                                        <div class="efecto_imagen">
                                            <svg width="60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z" />
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ $film->title }}</h5>
                                
                                <div class="efecto_imagen_artista">
                                    <div class="film-details">
                                        <p><strong>Precio:</strong> ${{ $film->rental_rate }}</p>
                                        <p><strong>Costo Reemplazo:</strong> ${{ $film->replacement_cost }}</p>
                                        <p><strong>Rating:</strong> {{ $film->rating }}</p>
                                        <p><strong>Duración Renta:</strong> {{ $film->rental_duration }} días</p>
                                        <p><strong>Descripción:</strong> {{ Str::limit($film->description, 100) }}</p>
                                    </div>

                                    <!-- Estado y botón -->
                                    @if($film->isAvailable())
                                        <span class="badge bg-success mb-2">✅ Disponible</span>
                                    @else
                                        <span class="badge bg-danger mb-2">🚫 Rentada</span>
                                    @endif

                                    <!-- Botón que abre modal -->
                                    <button class="btn btn-primary w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reserveModal{{ $film->film_id }}">
                                        @if($film->isAvailable())
                                            <svg width="1rem" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                            </svg>
                                            Reservar
                                        @else
                                            Ver Detalles
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal dentro del foreach -->
                <div class="modal fade" id="reserveModal{{ $film->film_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            
                            @if($film->isAvailable())
                                {{-- ✅ Caso: disponible --}}
                                <form action="{{ route('rent.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reservar {{ $film->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="film_id" value="{{ $film->film_id }}">
                                        
                                        <div class="mb-3">
                                            <label for="customer_id{{ $film->film_id }}" class="form-label">Seleccionar Cliente</label>
                                            <select name="customer_id" id="customer_id{{ $film->film_id }}" class="form-select" required>
                                                <option value="">Seleccione un cliente...</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->customer_id }}">
                                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                                        @if($customer->email)
                                                            ({{ $customer->email }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="alert alert-info">
                                            <strong>Detalles de la renta:</strong><br>
                                            Precio: ${{ $film->rental_rate }}<br>
                                            Duración: {{ $film->rental_duration }} días<br>
                                            Costo de reemplazo: ${{ $film->replacement_cost }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            
                            @else
                                {{-- 🚫 Caso: ya rentada --}}
                                @php
                                    $currentRental = $film->currentRental();
                                @endphp
                                
                                @if($currentRental)
                                    <form action="{{ route('rent.return', $currentRental->rental_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $film->title }} - Actualmente Rentada</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-warning">
                                                <p><strong>Esta película está actualmente rentada</strong></p>
                                                <p><strong>Cliente:</strong> {{ $currentRental->customer->first_name }} {{ $currentRental->customer->last_name }}</p>
                                                @if($currentRental->customer->email)
                                                    <p><strong>Email:</strong> {{ $currentRental->customer->email }}</p>
                                                @endif
                                                <p><strong>Fecha de renta:</strong> {{ $currentRental->rental_date->format('d/m/Y H:i') }}</p>
                                                <p><strong>Días transcurridos:</strong> {{ $currentRental->rental_date->diffInDays(now()) }} días</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">Procesar Devolución</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        'last_update'
<!DOCTYPE html>


<html lang="es">

        @include('parts.head')

<body>

@include('parts.header')


        <div id="cont_principal">
            <div class="container-fluid" id="container_a">
                
            @include('parts.nav')

                <div class="row">

                    <div class="col-sm">
                        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel" style="position:relative;">

                            <div class="carousel-inner">
                                <div class="carousel-item active" data-bs-interval="10000">
                                    <img src="images/carrusel1.png" class="d-block w-100" alt="...">
                                    <div class="capa_carrousel">
                                    <div class="contenido_carrousel">
                                        <div class="titulo_carrousel">Avegers</div>
                                        <div class="descriptores_carrousel">TV Movie<span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>2025</span><span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>Emision</span></div>
                                        <div>
                                            <div class="class_tags_carrousel">
                                                <?php for($l=0;$l<3;$l++): ?>
                                                    <span class="etiqueta_cinta_carrousel tag_item_carrousel">Tags</span>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="parrafo_carrousel"><p>Después de los devastadores eventos de Infinity War, el universo está en ruinas debido a las acciones de Thanos. Con la ayuda de los aliados que quedaron, los Vengadores se reagrupan una vez más para intentar deshacer las acciones de Thanos y restaurar el orden en el universo de una vez por todas, sin importar las consecuencias.</p></div>
                                        <div>
                                            <button class="boton_ver_album boton_ver_edicion">
                                                <svg width="1.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
                                                </svg>
                                                <span style="vertical-align:middle;">Ver ahora</span>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="images/carrusel2.webp" class="d-block w-100" alt="...">
                                    <div class="capa_carrousel">
                                    <div class="contenido_carrousel">
                                        <div class="titulo_carrousel">The Shawshank Redemption</div>
                                        <div class="descriptores_carrousel">TV Movie<span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>2025</span><span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>Emision</span></div>
                                        <div>
                                            <div class="class_tags_carrousel">
                                                <?php for($l=0;$l<3;$l++): ?>
                                                    <span class="etiqueta_cinta_carrousel tag_item_carrousel">Tags</span>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="parrafo_carrousel"><p>Viviendo en los barrios bajos de una ciudad adinerada, Rudo y su padre adoptivo Regto intentan coexistir con el resto de los habitantes del pueblo, pero Rudo desprecia el derroche de la clase alta. Ignorando las advertencias de quienes lo rodean, Rudo revisa regularmente la basura...</p></div>
                                        <div>
                                            <button class="boton_ver_album boton_ver_edicion">
                                                <svg width="1.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
                                                </svg>
                                                <span style="vertical-align:middle;">Ver ahora</span>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="images/carrusel3.jpg" class="d-block w-100" alt="...">
                                    <div class="capa_carrousel">
                                    <div class="contenido_carrousel">
                                        <div class="titulo_carrousel">Inception (2010)</div>
                                        <div class="descriptores_carrousel">TV Movie<span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>2025</span><span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>Emision</span></div>
                                        <div>
                                            <div class="class_tags_carrousel">
                                                <?php for($l=0;$l<3;$l++): ?>
                                                    <span class="etiqueta_cinta_carrousel tag_item_carrousel">Tags</span>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="parrafo_carrousel"><p>Un ladrón especializado en extraer secretos del subconsciente durante el estado de sueño, cuando la mente es más vulnerable, recibe la tarea inversa de plantar una idea en lugar de robarla. Dom Cobb y su equipo deben realizar una "inception" - implantar una idea profundamente en la mente de un heredero ...</p></div>
                                        <div>
                                            <button class="boton_ver_album boton_ver_edicion">
                                                <svg width="1.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
                                                </svg>
                                                <span style="vertical-align:middle;">Ver ahora</span>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <button class="carousel-control-prev boton_carrusel_izquierda" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                                <i class="fas fa-chevron-left" style="color: #a7acd1; font-size: 1rem;"></i>
                            </button>

                            <button class="carousel-control-next boton_carrusel_derecha" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                                <i class="fas fa-chevron-right" style="color: #a7acd1; font-size: 1rem;"></i>
                            </button>

                            

                        </div>


                    </div>

                </div>

            </div>


            <section class="contenedor_inicio">


                <section class="music_cards">
                        <div class="contorno_titulo_cartas"><span>Actores</span></div>
                    
                        <div class="cuadricula_contenido">
                             @foreach ($actors_tst as $actor)
                                <div class="estructure_card">
                                    <div class="contorno_imagen_carta">
                                        <div class="animacion_carta">
                                            <a href="#">
                                                    <img width="100%" src="images/logo1.jpg" alt="music">
                                                    <div class="efecto_imagen">
                                                        <svg width="60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z" />
                                                        </svg>
                                                    </div>
                                                    <div class="marca_musica">
                                                        <span>Actor</span>
                                                    </div>
                                                    <div class="marca_tipo">
                                                        <span>{{ $actor->last_update }}</span>
                                                    </div>
                                            </a>
                                        </div>
                                     </div>
                                    <div class=""><span>{{ $actor->first_name }} {{ $actor->last_name }}</span></div>
                                </div>
                            @endforeach
                        </div>



                                    

    <div class="contorno_titulo_cartas"><span>Reservar Pelicula</span></div>


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
                            INICIA SESION 
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal mejorado -->
        <div class="modal fade" id="reserveModal{{ $film->film_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                                                 <div class="modal-header">
                                                    <h5 style="color:black !important;" class="modal-title">Alerta</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
 <h5 style="color:black !important;" class="modal-title">INICIA SESSION PARA PODER RENTAR PELICULAS</h5>
                                                <div class="modal-body">
                                                
                                            </div>
                              
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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



    @include('parts.footer')
    @include('parts.scripts')
    

</body>


</html>
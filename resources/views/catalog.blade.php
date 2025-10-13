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

             <div class="contorno_titulo_cartas" style="margin-top:2rem;"><span>Actores</span></div>
                    
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

            <!-- SECCIÓN DE BÚSQUEDA Y FILTROS -->
            <div class="row mb-4 mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-search"></i> Buscar Películas
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('catalog') }}" method="GET" id="searchForm">
                                <div class="row g-3">
                                    <!-- Búsqueda por título -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-film"></i> Título de la película
                                        </label>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Ej: Avengers, Inception..." 
                                               value="{{ request('search') }}">
                                    </div>

                                    <!-- Filtro por categoría -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-tag"></i> Categoría
                                        </label>
                                        <select name="category" class="form-select">
                                            <option value="">Todas las categorías</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}" 
                                                    {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro por actor -->
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="fas fa-user"></i> Actor
                                        </label>
                                        <select name="actor" class="form-select">
                                            <option value="">Todos los actores</option>
                                            @foreach($actors as $actor)
                                                <option value="{{ $actor->actor_id }}"
                                                    {{ request('actor') == $actor->actor_id ? 'selected' : '' }}>
                                                    {{ $actor->first_name }} {{ $actor->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro por idioma -->
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="fas fa-language"></i> Idioma
                                        </label>
                                        <select name="language" class="form-select">
                                            <option value="">Todos los idiomas</option>
                                            @foreach($languages as $language)
                                                <option value="{{ $language->language_id }}"
                                                    {{ request('language') == $language->language_id ? 'selected' : '' }}>
                                                    {{ $language->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro por disponibilidad -->
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="fas fa-check-circle"></i> Disponibilidad
                                        </label>
                                        <select name="available" class="form-select">
                                            <option value="">Todas</option>
                                            <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>
                                                Solo disponibles
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                        <a href="{{ route('catalog') }}" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Limpiar filtros
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESULTADOS DE BÚSQUEDA -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ $films->total() }}</strong> películas encontradas
                        @if(request()->hasAny(['search', 'category', 'actor', 'language', 'available']))
                            (filtrado activo)
                        @endif
                    </div>
                </div>
            </div>

            <!-- CATÁLOGO DE PELÍCULAS -->
            <section class="contenedor_inicio">
                <div class="contorno_titulo_cartas">
                    <span>Catálogo de Películas Disponibles</span>
                </div>

                <div class="cuadricula_contenido">
                    @forelse ($films as $film)
                        <div class="seccion_artista">
                            <div class="contenedor_albumes edit_artistas" style="border-radius:0.4rem;">
                                <div class="animacion_carta" style="border-radius:0.4rem;">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#filmModal{{ $film->film_id }}">
                                        <img width="100%" src="images/imagen_alargada2.jpg" alt="{{ $film->title }}">
                                        <div class="efecto_imagen">
                                            <svg width="60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </div>
                                        
                                        <!-- Badge de disponibilidad -->
                                        <div class="marca_album marca_artista">
                                            @if($film->isAvailable())
                                                <span class="badge bg-success">✅ Disponible</span>
                                            @else
                                                <span class="badge bg-danger">🚫 No disponible</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Rating -->
                                        <div class="marca_formato formato_artista">
                                            <span class="badge bg-warning text-dark">{{ $film->rating }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div style="margin:10px;">
                                <span style="font-weight: bold;">{{ $film->title }}</span>
                            </div>
                            
                            <div style="margin:10px; font-size: 0.9rem; color: #666;">
                                <div><i class="fas fa-tag"></i> {{ $film->categories->pluck('name')->join(', ') ?: 'Sin categoría' }}</div>
                                <div><i class="fas fa-clock"></i> {{ $film->length }} min</div>
                                <div><i class="fas fa-dollar-sign"></i> ${{ $film->rental_rate }} / {{ $film->rental_duration }} días</div>
                            </div>

                            <div style="text-align:center; margin: 10px;">
                                <button class="btn btn-sm btn-primary w-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#filmModal{{ $film->film_id }}">
                                    <i class="fas fa-info-circle"></i> Ver Detalles
                                </button>
                            </div>
                        </div>

                        <!-- MODAL CON DETALLES COMPLETOS -->
                        <div class="modal fade" id="filmModal{{ $film->film_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-film"></i> {{ $film->title }}
                                            @if($film->release_year)
                                                <span class="badge bg-secondary">{{ $film->release_year }}</span>
                                            @endif
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <!-- Imagen destacada -->
                                        <div class="text-center mb-4">
                                            <img src="images/imagen_alargada2.jpg" 
                                                 alt="{{ $film->title }}" 
                                                 class="img-fluid rounded shadow"
                                                 style="max-height: 300px;">
                                        </div>

                                        <!-- Sinopsis -->
                                        <div class="mb-4">
                                            <h6 class="border-bottom pb-2">
                                                <i class="fas fa-book-open"></i> Sinopsis
                                            </h6>
                                            <p class="text-muted">{{ $film->description ?: 'Sin descripción disponible' }}</p>
                                        </div>

                                        <!-- Información detallada -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">
                                                    <i class="fas fa-info-circle"></i> Información General
                                                </h6>
                                                <ul class="list-unstyled">
                                                    <li><strong>Duración:</strong> {{ $film->length }} minutos</li>
                                                    <li><strong>Rating:</strong> 
                                                        <span class="badge bg-warning text-dark">{{ $film->rating }}</span>
                                                    </li>
                                                    <li><strong>Idioma:</strong> {{ $film->language->name ?? 'No especificado' }}</li>
                                                    <li><strong>Categorías:</strong> 
                                                        {{ $film->categories->pluck('name')->join(', ') ?: 'Sin categoría' }}
                                                    </li>
                                                    @if($film->special_features)
                                                        <li><strong>Extras:</strong> {{ $film->special_features }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2">
                                                    <i class="fas fa-dollar-sign"></i> Precio y Disponibilidad
                                                </h6>
                                                <ul class="list-unstyled">
                                                    <li><strong>Precio de renta:</strong> ${{ $film->rental_rate }}</li>
                                                    <li><strong>Duración de renta:</strong> {{ $film->rental_duration }} días</li>
                                                    <li><strong>Costo de reemplazo:</strong> ${{ $film->replacement_cost }}</li>
                                                    <li>
                                                        <strong>Estado:</strong>
                                                        @if($film->isAvailable())
                                                            <span class="badge bg-success">✅ {{ $film->availableCopies() }} copias disponibles</span>
                                                        @else
                                                            <span class="badge bg-danger">🚫 Todas rentadas</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Actores -->
                                        @if($film->actors->count() > 0)
                                            <div class="mb-4">
                                                <h6 class="border-bottom pb-2">
                                                    <i class="fas fa-users"></i> Elenco Principal
                                                </h6>
                                                <div class="row">
                                                    @foreach($film->actors->take(8) as $actor)
                                                        <div class="col-6 col-md-3 mb-2">
                                                            <div class="card text-center">
                                                                <div class="card-body p-2">
                                                                    <small>{{ $actor->first_name }} {{ $actor->last_name }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if($film->actors->count() > 8)
                                                    <small class="text-muted">
                                                        Y {{ $film->actors->count() - 8 }} actores más...
                                                    </small>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Disponibilidad por sucursal -->
                                        <div class="mb-3">
                                            <h6 class="border-bottom pb-2">
                                                <i class="fas fa-store"></i> Disponibilidad por Sucursal
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Sucursal</th>
                                                            <th>Disponibles</th>
                                                            <th>Total</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($film->inventory->groupBy('store_id') as $storeId => $items)
                                                            @php
                                                                $available = $items->filter(function($item) {
                                                                    return !$item->rentals()->whereNull('return_date')->exists();
                                                                })->count();
                                                                $total = $items->count();
                                                            @endphp
                                                            <tr>
                                                                <td>Sucursal {{ $storeId }}</td>
                                                                <td>{{ $available }}</td>
                                                                <td>{{ $total }}</td>
                                                                <td>
                                                                    @if($available > 0)
                                                                        <span class="badge bg-success">Disponible</span>
                                                                    @else
                                                                        <span class="badge bg-danger">No disponible</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cerrar
                                        </button>
                                        @if($film->isAvailable())
                                            <button type="button" class="btn btn-primary">
                                                <i class="fas fa-shopping-cart"></i> Solicitar Renta
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>No se encontraron películas</strong> con los filtros seleccionados.
                                <br>
                                <a href="{{ route('catalog') }}" class="btn btn-sm btn-primary mt-2">
                                    Ver todo el catálogo
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                <div class="container mt-4 mb-5">
                    <div class="d-flex justify-content-center">
                        <div class="pagination-wrapper">
                            {{ $films->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('parts.footer')
    @include('parts.scripts')
</body>
</html>
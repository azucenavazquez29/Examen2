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

                
<!-- Formulario de Filtros - Coloca esto ANTES del div con class="cuadricula_contenido" -->
<div class="container mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <svg width="1.2rem" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-inline">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                </svg>
                Filtrar Películas
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('films_filter.index') }}" method="GET">
                <div class="row g-3">
                    
                    <!-- Filtro por Título -->
                    <div class="col-md-6 col-lg-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" 
                               class="form-control" 
                               id="title" 
                               name="title" 
                               placeholder="Buscar por título..."
                               value="{{ request('title') }}">
                    </div>

                    <!-- Filtro por Categoría -->
                    <div class="col-md-6 col-lg-3">
                        <label for="category" class="form-label">Categoría</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" 
                                    {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Actor -->
                    <div class="col-md-6 col-lg-3">
                        <label for="actor" class="form-label">Actor</label>
                        <select class="form-select" id="actor" name="actor">
                            <option value="">Todos los actores</option>
                            @foreach($actors as $actor)
                                <option value="{{ $actor->actor_id }}" 
                                    {{ request('actor') == $actor->actor_id ? 'selected' : '' }}>
                                    {{ $actor->first_name }} {{ $actor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Idioma -->
                    <div class="col-md-6 col-lg-3">
                        <label for="language" class="form-label">Idioma</label>
                        <select class="form-select" id="language" name="language">
                            <option value="">Todos los idiomas</option>
                            @foreach($languages as $language)
                                <option value="{{ $language->language_id }}" 
                                    {{ request('language') == $language->language_id ? 'selected' : '' }}>
                                    {{ $language->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Rating -->
                    <div class="col-md-6 col-lg-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="">Todos</option>
                            <option value="G" {{ request('rating') == 'G' ? 'selected' : '' }}>G</option>
                            <option value="PG" {{ request('rating') == 'PG' ? 'selected' : '' }}>PG</option>
                            <option value="PG-13" {{ request('rating') == 'PG-13' ? 'selected' : '' }}>PG-13</option>
                            <option value="R" {{ request('rating') == 'R' ? 'selected' : '' }}>R</option>
                            <option value="NC-17" {{ request('rating') == 'NC-17' ? 'selected' : '' }}>NC-17</option>
                        </select>
                    </div>

                    <!-- Filtro por Precio de Renta -->
                    <div class="col-md-6 col-lg-3">
                        <label for="rental_rate" class="form-label">Precio Máximo</label>
                        <input type="number" 
                               class="form-control" 
                               id="rental_rate" 
                               name="rental_rate" 
                               step="0.01"
                               placeholder="Ej: 4.99"
                               value="{{ request('rental_rate') }}">
                    </div>

                    <!-- Filtro por Disponibilidad -->
                    <div class="col-md-6 col-lg-3">
                        <label for="available" class="form-label">Disponibilidad</label>
                        <select class="form-select" id="available" name="available">
                            <option value="">Todas</option>
                            <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Solo disponibles</option>
                            <option value="0" {{ request('available') == '0' ? 'selected' : '' }}>No disponibles</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <svg width="1rem" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-inline">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            Aplicar Filtros
                        </button>
                        <a href="{{ route('films_filter.index') }}" class="btn btn-secondary">
                            <svg width="1rem" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-inline">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Limpiar Filtros
                        </a>
                        
                        @if(request()->hasAny(['title', 'category', 'actor', 'language', 'rating', 'rental_rate', 'available']))
                            <span class="badge bg-info ms-2">
                                Filtros activos: {{ collect(request()->only(['title', 'category', 'actor', 'language', 'rating', 'rental_rate', 'available']))->filter()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
                                                    @if(Session::get('user_role') === 'admin')
                                                        <strong>{{ $film->availableCopies() }}</strong><br>
                                                        <small>Disponibles (Todas)</small>
                                                    @else
                                                        <strong>{{ $film->availableCopiesInStore(Session::get('store_id')) }}</strong><br>
                                                        <small>Disponibles (Mi sucursal)</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded">
                                                    @if(Session::get('user_role') === 'admin')
                                                        <strong>{{ $film->totalCopies() }}</strong><br>
                                                        <small>Total (Todas)</small>
                                                    @else
                                                        <strong>{{ $film->totalCopiesInStore(Session::get('store_id')) }}</strong><br>
                                                        <small>Total (Mi sucursal)</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

<!-- Estado y botón -->
@php
    // ✅ SOLUCIÓN: Calcular CORRECTAMENTE las rentas activas POR SUCURSAL
    if ($userRole === 'admin') {
        // Admin: ver todo globalmente
        $availableCount = $film->availableCopies();
        $totalCount = $film->totalCopies();
        
        // Rentas activas de TODAS las sucursales
        $activeRentals = \DB::table('rental')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->join('customer', 'rental.customer_id', '=', 'customer.customer_id')
            ->where('inventory.film_id', $film->film_id)
            ->whereNull('rental.return_date')
            ->select(
                'rental.rental_id',
                'rental.rental_date',
                'rental.return_date',
                'inventory.store_id',
                'inventory.inventory_id',
                'customer.customer_id',
                'customer.first_name',
                'customer.last_name',
                'customer.email'
            )
            ->orderBy('rental.rental_date', 'desc')
            ->get();
            
    } else {
        // Empleado: SOLO su sucursal
        $totalCount = $film->inventory()->where('store_id', $storeId)->count();
        
        // ✅ CLAVE: Contar copias disponibles SOLO de esta sucursal
        $inventoryIdsInStore = $film->inventory()
            ->where('store_id', $storeId)
            ->pluck('inventory_id')
            ->toArray();
        
        // Contar cuántas de ESAS copias están rentadas
        $rentedInStore = \DB::table('rental')
            ->whereIn('inventory_id', $inventoryIdsInStore)
            ->whereNull('return_date')
            ->count();
        
        $availableCount = max(0, $totalCount - $rentedInStore);
        
        // Rentas activas SOLO de esta sucursal
        $activeRentals = \DB::table('rental')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->join('customer', 'rental.customer_id', '=', 'customer.customer_id')
            ->where('inventory.film_id', $film->film_id)
            ->where('inventory.store_id', $storeId) // ✅ FILTRO POR SUCURSAL
            ->whereNull('rental.return_date')
            ->select(
                'rental.rental_id',
                'rental.rental_date',
                'rental.return_date',
                'inventory.store_id',
                'inventory.inventory_id',
                'customer.customer_id',
                'customer.first_name',
                'customer.last_name',
                'customer.email'
            )
            ->orderBy('rental.rental_date', 'desc')
            ->get();
    }
    
    $isAvailable = $availableCount > 0;
@endphp

                        @if($isAvailable)
                            <div style="text-align:center;">
                                <span style="padding:1rem;font-size:0.7rem;" class="badge bg-success mb-2">
                                    ✅ Disponible ({{ $availableCount }} copias)
                                </span>
                            </div>
                        @else
                            <div style="text-align:center;">
                                <span style="padding:1rem;font-size:0.7rem;" class="badge bg-danger mb-2">
                                    🚫 No disponible
                                </span>
                            </div>
                        @endif
                        <!-- Botón que abre modal -->
                        <button class="btn w-100 {{ $isAvailable ? 'btn-primary' : 'btn-warning' }}"
                                data-bs-toggle="modal"
                                data-bs-target="#reserveModal{{ $film->film_id }}">
                            @if($isAvailable)
                                <svg width="1rem" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                </svg>
                                @if(count($activeRentals) > 0)
                                    Rentar y Gestionar ({{ count($activeRentals) }} activas)
                                @else
                                    Reservar Película
                                @endif
                            @else
                                <svg width="1rem" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                                Gestionar Rentas ({{ count($activeRentals) }})
                            @endif
                        </button>
                                        </div>
                                    </div>
                                </div>

<!-- Modal mejorado -->
<div class="modal fade" id="reserveModal{{ $film->film_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            @if($isAvailable)
                {{-- ✅ Caso: disponible - Mostrar formulario de renta Y tabla de devoluciones --}}
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $film->title }} - Reservar y Gestionar
                        @if($userRole !== 'admin')
                            <span class="badge bg-info">Mi Sucursal #{{ $storeId }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Info de inventario -->
                    <div class="alert alert-success">
                        <strong>Estado del inventario:</strong><br>
                        @if($userRole === 'admin')
                            Copias disponibles: {{ $availableCount }} de {{ $totalCount }} (Todas las sucursales)
                        @else
                            Copias disponibles: {{ $availableCount }} de {{ $totalCount }} (Sucursal #{{ $storeId }})
                        @endif
                    </div>

                    <!-- Sección de nueva renta -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Nueva Renta</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('rental.store') }}" method="POST">
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
                                    <small class="text-muted">
                                        @if($userRole === 'admin')
                                            Clientes de todas las sucursales
                                        @else
                                            Clientes de tu sucursal #{{ $storeId }}
                                        @endif
                                    </small>
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
@if(count($activeRentals) > 0)
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0">
                Rentas Activas ({{ count($activeRentals) }})
                @if($userRole !== 'admin')
                    - Mi Sucursal #{{ $storeId }}
                @endif
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Email</th>
                            @if($userRole === 'admin')
                                <th>Sucursal</th>
                            @endif
                            <th>Fecha Renta</th>
                            <th>Días</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeRentals as $rental)
                            <tr>
                                <td>{{ $rental->first_name }} {{ $rental->last_name }}</td>
                                <td>{{ $rental->email ?? 'N/A' }}</td>
                                @if($userRole === 'admin')
                                    <td>
                                        <span class="badge bg-info">
                                            Sucursal #{{ $rental->store_id }}
                                        </span>
                                    </td>
                                @endif
                                <td>{{ \Carbon\Carbon::parse($rental->rental_date)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $daysRented = \Carbon\Carbon::parse($rental->rental_date)->diffInDays(now());
                                        $isOverdue = $daysRented > $film->rental_duration;
                                    @endphp
                                    <span class="badge {{ $isOverdue ? 'bg-danger' : 'bg-info' }}">
                                        {{ $daysRented }} días
                                        @if($isOverdue) - VENCIDO @endif
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('rental.return', $rental->rental_id) }}" method="POST" style="display: inline;">
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
        <h5 class="modal-title">
            {{ $film->title }} - Todas las copias rentadas
            @if($userRole !== 'admin')
                <span class="badge bg-danger">Mi Sucursal #{{ $storeId }}</span>
            @endif
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <strong>No hay copias disponibles</strong><br>
            @if($userRole === 'admin')
                Total de copias: {{ $totalCount }} (Todas las sucursales)<br>
            @else
                Total de copias en tu sucursal: {{ $totalCount }}<br>
            @endif
            Todas están actualmente rentadas
        </div>

        <h6>Lista de rentas activas:</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Email</th>
                        @if($userRole === 'admin')
                            <th>Sucursal</th>
                        @endif
                        <th>Fecha Renta</th>
                        <th>Días</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeRentals as $rental)
                        <tr>
                            <td>{{ $rental->first_name }} {{ $rental->last_name }}</td>
                            <td>{{ $rental->email ?? 'N/A' }}</td>
                            @if($userRole === 'admin')
                                <td>
                                    <span class="badge bg-info">
                                        Sucursal #{{ $rental->store_id }}
                                    </span>
                                </td>
                            @endif
                            <td>{{ \Carbon\Carbon::parse($rental->rental_date)->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $daysRented = \Carbon\Carbon::parse($rental->rental_date)->diffInDays(now());
                                    $isOverdue = $daysRented > $film->rental_duration;
                                @endphp
                                <span class="badge {{ $isOverdue ? 'bg-danger' : 'bg-info' }}">
                                    {{ $daysRented }} días
                                    @if($isOverdue) - VENCIDO @endif
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('rental.return', $rental->rental_id) }}" method="POST" style="display: inline;">
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

<style>
.pagination svg {
    display: none !important;
}

.pagination .page-link::before {
    content: attr(aria-label);
}

.pagination .page-item:first-child .page-link::before {
    content: '‹';
    font-size: 20px;
}

.pagination .page-item:last-child .page-link::before {
    content: '›';
    font-size: 20px;
}
</style>

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
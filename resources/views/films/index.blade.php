@include('parts.head')
@include('parts.header')

<div id="cont_principal">
    <div class="container-fluid" id="container_a">
        @include('parts.nav')

        <h1 class="text-center my-4 display-5 fw-bold text-light">🎬 Películas</h1>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('films.create') }}" class="btn btn-success btn-lg shadow">
                ➕ Nueva Película
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover table-bordered shadow-lg rounded-3 overflow-hidden align-middle">
                <thead class="text-center">
                    <tr>
                        <th>Título</th>
                        <th>Año</th>
                        <th>Duración</th>
                        <th>Precio Renta</th>
                        <th>Clasificación</th>
                        <th>Categorías</th>
                        <th>Actores</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($films as $film)
                        <tr>
                            <td class="fw-bold" title="{{ $film->description }}">
                                {{ $film->title }}
                            </td>
                            <td class="text-center">{{ $film->release_year ?? '-' }}</td>
                            <td class="text-center">
                                {{ $film->length ? $film->length . ' min' : '-' }}
                            </td>
                            <td class="text-center">${{ number_format($film->rental_rate, 2) }}</td>
                            <td class="text-center">
                                @php
                                    $colors = [
                                        'G' => 'success',
                                        'PG' => 'info',
                                        'PG-13' => 'warning',
                                        'R' => 'danger',
                                        'NC-17' => 'dark',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colors[$film->rating] ?? 'secondary' }}">
                                    {{ $film->rating ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @forelse($film->categories as $cat)
                                    <span class="badge bg-secondary m-1">{{ $cat->name }}</span>
                                @empty
                                    <span class="text-muted fst-italic">Sin categoría</span>
                                @endforelse
                            </td>
                            <td>
                                @forelse($film->actors as $actor)
                                    <span class="badge bg-primary text-light m-1">
                                        {{ $actor->first_name }} {{ $actor->last_name }}
                                    </span>
                                @empty
                                    <span class="text-muted fst-italic">Sin actores</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <a href="{{ route('films.show', $film) }}" class="btn btn-sm btn-primary me-1 shadow-sm" data-bs-toggle="tooltip" title="Ver detalles">
                                    👁 Ver
                                </a>
                                <a href="{{ route('films.edit', $film) }}" class="btn btn-sm btn-warning shadow-sm" data-bs-toggle="tooltip" title="Editar película">
                                    ✏️ Editar
                                </a>

                                <form action="{{ route('films.destroy', $film) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                                        onclick="return confirm('¿Seguro que deseas eliminar la película {{ $film->title }}?');"
                                        data-bs-toggle="tooltip" title="Eliminar película">
                                        🗑 Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay películas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div> {{-- cierre container-fluid --}}
</div> {{-- cierre cont_principal --}}

@include('parts.footer')
@include('parts.scripts')

@include('parts.head')
@include('parts.header')

<div id="cont_principal">
    <div class="container-fluid" id="container_a">
        @include('parts.nav')

        <h1 class="text-center my-4 display-5 fw-bold" style="color:white !important; font-weight:bolder !important;">
            <i class="fas fa-chart-line me-2"></i>Importacion desde el API de OMDB
        </h1>


    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-download"></i> Importar Película desde OMDb
                        </h4>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>¡Error!</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="importForm" action="{{ route('films.import-omdb') }}" method="POST">
                            @csrf

                            <!-- Búsqueda de película -->
                            <div class="mb-4">
                                <label for="search_query" class="form-label">
                                    <strong>Título de la Película</strong>
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        id="search_query" 
                                        name="search_query"
                                        class="form-control @error('search_query') is-invalid @enderror"
                                        placeholder="Ej: The Matrix, Inception, The Dark Knight..."
                                        value="{{ old('search_query') }}"
                                        required
                                    >
                                    <button 
                                        type="button" 
                                        id="searchBtn" 
                                        class="btn btn-outline-primary"
                                        onclick="searchMovie()"
                                    >
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Ingresa el título de la película para buscarla en OMDb
                                </small>
                            </div>

                            <!-- Información de la película (se llena con AJAX) -->
                            <div id="movieInfo" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <img id="poster" src="" alt="Poster" class="img-fluid rounded border">
                                    </div>
                                    <div class="col-md-9">
                                        <h5 id="movieTitle"></h5>
                                        <p>
                                            <strong>Año:</strong> <span id="movieYear"></span><br>
                                            <strong>Duración:</strong> <span id="movieLength"></span> minutos<br>
                                            <strong>Rating IMDb:</strong> <span id="movieRating"></span>/10<br>
                                            <strong>Calificación:</strong> <span id="movieClassification"></span><br>
                                            <strong>Director:</strong> <span id="movieDirector"></span>
                                        </p>
                                        <p>
                                            <strong>Sinopsis:</strong><br>
                                            <span id="movieDescription" class="text-muted"></span>
                                        </p>
                                        <p>
                                            <strong>Actores:</strong><br>
                                            <span id="movieActors" class="text-muted"></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="alert alert-warning" id="duplicateWarning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Advertencia:</strong> Esta película ya existe en la base de datos.
                                </div>

                                <hr>
                            </div>

                            <!-- Configuración de la película -->
                            <div id="configSection" style="display: none;">
                                <h5 class="mb-3">Configuración de Renta</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="language_id" class="form-label">
                                            <strong>Idioma</strong>
                                        </label>
                                        <select 
                                            id="language_id" 
                                            name="language_id" 
                                            class="form-select @error('language_id') is-invalid @enderror"
                                            required
                                        >
                                            <option value="">Selecciona un idioma</option>
                                            @foreach ($languages as $language)
                                                <option value="{{ $language->language_id }}" 
                                                    {{ old('language_id') == $language->language_id ? 'selected' : '' }}>
                                                    {{ $language->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="rental_duration" class="form-label">
                                            <strong>Duración de Renta (días)</strong>
                                        </label>
                                        <input 
                                            type="number" 
                                            id="rental_duration" 
                                            name="rental_duration"
                                            class="form-control @error('rental_duration') is-invalid @enderror"
                                            min="1" 
                                            max="10"
                                            value="{{ old('rental_duration', 3) }}"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="rental_rate" class="form-label">
                                            <strong>Precio de Renta ($)</strong>
                                        </label>
                                        <input 
                                            type="number" 
                                            id="rental_rate" 
                                            name="rental_rate"
                                            class="form-control @error('rental_rate') is-invalid @enderror"
                                            step="0.01" 
                                            min="0.99"
                                            value="{{ old('rental_rate', 4.99) }}"
                                            required
                                        >
                                    </div>

                                    <div class="col-md-6">
                                        <label for="replacement_cost" class="form-label">
                                            <strong>Costo de Reemplazo ($)</strong>
                                        </label>
                                        <input 
                                            type="number" 
                                            id="replacement_cost" 
                                            name="replacement_cost"
                                            class="form-control @error('replacement_cost') is-invalid @enderror"
                                            step="0.01" 
                                            min="1"
                                            value="{{ old('replacement_cost', 19.99) }}"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="categories" class="form-label">
                                        <strong>Categorías</strong>
                                    </label>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($categories as $category)
                                            <div class="form-check">
                                                <input 
                                                    type="checkbox" 
                                                    id="category_{{ $category->category_id }}" 
                                                    name="categories[]" 
                                                    value="{{ $category->category_id }}"
                                                    class="form-check-input"
                                                    {{ in_array($category->category_id, old('categories', [])) ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="category_{{ $category->category_id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('categories')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success" id="submitBtn">
                                        <i class="fas fa-save"></i> Importar Película
                                    </button>
                                    <a href="{{ route('films.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>

                            <!-- Estado de carga -->
                            <div id="loadingState" class="text-center" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Buscando...</span>
                                </div>
                                <p class="mt-2">Buscando película en OMDb...</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    async function searchMovie() {
        const query = document.getElementById('search_query').value.trim();
        
        if (!query) {
            alert('Por favor ingresa un título de película');
            return;
        }

        // Mostrar estado de carga
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('movieInfo').style.display = 'none';
        document.getElementById('configSection').style.display = 'none';

        try {
            const response = await fetch('{{ route("films.search-omdb") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ query: query })
            });

            const result = await response.json();

            document.getElementById('loadingState').style.display = 'none';

            if (!result.success) {
                alert('Error: ' + result.error);
                return;
            }

            // Llenar información de la película
            const data = result.data;
            document.getElementById('movieTitle').textContent = data.title;
            document.getElementById('movieYear').textContent = data.release_year || 'N/A';
            document.getElementById('movieLength').textContent = data.length || 'N/A';
            document.getElementById('movieRating').textContent = data.imdb_rating || 'N/A';
            document.getElementById('movieClassification').textContent = data.rating || 'N/A';
            document.getElementById('movieDirector').textContent = data.director || 'N/A';
            document.getElementById('movieDescription').textContent = data.description || 'Sin sinopsis disponible';
            document.getElementById('movieActors').textContent = 
                data.actors.length > 0 ? data.actors.join(', ') : 'No disponible';
            
            if (data.poster && data.poster !== 'N/A') {
                document.getElementById('poster').src = data.poster;
            } else {
                document.getElementById('poster').src = 'https://via.placeholder.com/150x200?text=Sin+Imagen';
            }

            // Mostrar advertencia si existe duplicado
            if (result.exists) {
                document.getElementById('duplicateWarning').style.display = 'block';
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').classList.add('disabled');
            } else {
                document.getElementById('duplicateWarning').style.display = 'none';
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').classList.remove('disabled');
            }

            document.getElementById('movieInfo').style.display = 'block';
            document.getElementById('configSection').style.display = 'block';

        } catch (error) {
            document.getElementById('loadingState').style.display = 'none';
            console.error('Error:', error);
            alert('Error al buscar la película. Intenta nuevamente.');
        }
    }

    // Permitir buscar al presionar Enter
    document.getElementById('search_query').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchMovie();
        }
    });
    </script>

    <style>
    #poster {
        max-width: 150px;
        height: auto;
        object-fit: cover;
    }

    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .card {
        border: none;
        border-radius: 8px;
    }

    .card-header {
        border-radius: 8px 8px 0 0 !important;
    }
    </style>


@include('parts.footer')
@include('parts.scripts')

@include('parts.head')
@include('parts.header')

<div id="cont_principal">
    <div class="container-fluid" id="container_a">
        @include('parts.nav')

        <div class="container my-5">
            <div class="card shadow-lg rounded-4 border-0 bg-dark text-white">
                <div class="card-header bg-gradient bg-dark text-warning text-center py-4">
                    <h1 class="display-5 fw-bold">🎬 Editar Película</h1>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('films.update', $film->film_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Errores de validación --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Título --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold text-warning">Título</label>
                            <input type="text" name="title" id="title"
                                class="form-control form-control-lg shadow-sm bg-secondary text-white border-0"
                                placeholder="Nombre de la película" value="{{ old('title', $film->title) }}">
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold text-warning">Descripción</label>
                            <textarea name="description" id="description"
                                class="form-control shadow-sm bg-secondary text-white border-0"
                                rows="4" placeholder="Descripción de la película">{{ old('description', $film->description) }}</textarea>
                        </div>

                        <div class="row">
                            {{-- Año --}}
                            <div class="col-md-4 mb-4">
                                <label for="release_year" class="form-label fw-semibold text-warning">Año</label>
                                <input type="number" name="release_year" id="release_year"
                                    class="form-control shadow-sm bg-secondary text-white border-0"
                                    value="{{ old('release_year', $film->release_year) }}">
                            </div>

                            {{-- Idioma principal --}}
                            <div class="col-md-4 mb-4">
                                <label for="language_id" class="form-label fw-semibold text-warning">Idioma</label>
                                <select name="language_id" id="language_id"
                                    class="form-select shadow-sm bg-secondary text-white border-0">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($languages as $lang)
                                        <option value="{{ $lang->language_id }}"
                                            {{ old('language_id', $film->language_id) == $lang->language_id ? 'selected' : '' }}>
                                            {{ $lang->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Idioma original --}}
                            <div class="col-md-4 mb-4">
                                <label for="original_language_id" class="form-label fw-semibold text-warning">Idioma Original</label>
                                <select name="original_language_id" id="original_language_id"
                                    class="form-select shadow-sm bg-secondary text-white border-0">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($languages as $lang)
                                        <option value="{{ $lang->language_id }}"
                                            {{ old('original_language_id', $film->original_language_id) == $lang->language_id ? 'selected' : '' }}>
                                            {{ $lang->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="rental_duration" class="form-label fw-semibold text-warning">Duración renta (días)</label>
                                <input type="number" name="rental_duration" id="rental_duration"
                                    class="form-control shadow-sm bg-secondary text-white border-0"
                                    value="{{ old('rental_duration', $film->rental_duration) }}">
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="rental_rate" class="form-label fw-semibold text-warning">Tarifa renta</label>
                                <input type="text" name="rental_rate" id="rental_rate"
                                    class="form-control shadow-sm bg-secondary text-white border-0"
                                    placeholder="$0.00" value="{{ old('rental_rate', $film->rental_rate) }}">
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="length" class="form-label fw-semibold text-warning">Duración (min)</label>
                                <input type="number" name="length" id="length"
                                    class="form-control shadow-sm bg-secondary text-white border-0"
                                    value="{{ old('length', $film->length) }}">
                            </div>
                        </div>

                        {{-- Costo de reemplazo --}}
                        <div class="mb-4">
                            <label for="replacement_cost" class="form-label fw-semibold text-warning">Costo de reemplazo</label>
                            <input type="text" name="replacement_cost" id="replacement_cost"
                                class="form-control shadow-sm bg-secondary text-white border-0"
                                placeholder="$0.00" value="{{ old('replacement_cost', $film->replacement_cost) }}">
                        </div>

                        {{-- Clasificación --}}
                        <div class="mb-4">
                            <label for="rating" class="form-label fw-semibold text-warning">Clasificación</label>
                            <select name="rating" id="rating"
                                class="form-select shadow-sm bg-secondary text-white border-0">
                                <option value="">-- Seleccionar --</option>
                                @foreach (['G', 'PG', 'PG-13', 'R', 'NC-17'] as $rate)
                                    <option value="{{ $rate }}" {{ old('rating', $film->rating) == $rate ? 'selected' : '' }}>
                                        {{ $rate }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Categorías --}}
                        <div class="mb-4">
                            <label for="categories" class="form-label fw-semibold text-warning">Categorías</label>
                            <select name="categories[]" id="categories"
                                class="form-select shadow-sm bg-secondary text-white border-0" multiple>
                                @php
                                    $selectedCategories = old('categories', $film->categories->pluck('category_id')->toArray());
                                @endphp
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category_id }}" {{ in_array($cat->category_id, $selectedCategories) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Mantén presionada CTRL (o ⌘ en Mac) para seleccionar varias</small>
                        </div>


                        <div class="text-center mt-5">
                            <button type="submit"
                                class="btn btn-warning btn-lg shadow-lg px-5 py-3 fw-bold text-dark">
                                💾 Actualizar Película
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('parts.footer')
        @include('parts.scripts')
    </div>
</div>

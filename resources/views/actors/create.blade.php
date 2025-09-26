@include('parts.head')
@include('parts.header')

<div id="cont_principal">
    <div class="container my-5">
        <div class="card shadow-lg rounded-4 border-0 bg-dark text-white">
            <div class="card-header text-warning text-center py-4">
                <h1 class="display-5 fw-bold">👤 Nuevo Actor</h1>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('actors.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label for="first_name" class="form-label text-warning fw-semibold">Nombre</label>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                            value="{{ old('first_name') }}">
                    </div>

                    {{-- Apellido --}}
                    <div class="mb-4">
                        <label for="last_name" class="form-label text-warning fw-semibold">Apellido</label>
                        <input type="text" name="last_name" id="last_name" class="form-control"
                            value="{{ old('last_name') }}">
                    </div>

                    {{-- Películas (opcional) --}}
                    <div class="mb-4">
                        <label class="form-label text-warning fw-semibold">Películas</label>
                        <select name="films[]" class="form-select" multiple>
                            @foreach ($films as $film)
                                <option value="{{ $film->film_id }}" {{ in_array($film->film_id, old('films', [])) ? 'selected' : '' }}>
                                    {{ $film->title }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Mantén presionada CTRL (o ⌘ en Mac) para seleccionar varias</small>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-warning fw-bold">💾 Guardar Actor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('parts.footer')
@include('parts.scripts')

@include('parts.head')
@include('parts.header')
<div id="cont_principal">
    <div class="container-fluid" id="container_a">
    @include('parts.nav')


<h1 class="text-center my-4 display-5 fw-bold text-success">Idiomas</h1>

<div class="d-flex justify-content-end mb-3">
    <a href="{{route('languages.create')}}" class="btn btn-success btn-lg shadow">
        ➕ Nuevo Idioma
    </a>
</div>

<div class="table-responsive">
    <table class="table table-dark table-striped table-hover table-bordered shadow-lg rounded-3 overflow-hidden align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Idioma</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($languages as $language)
            <tr>
                <td class="fw-bold">{{$language->name}}</td>
                <td class="text-center">
                    <a href="{{route('languages.edit',$language)}}" class="btn btn-sm btn-warning me-1 shadow-sm">
                        ✏️ Editar
                    </a>
                    <form action="{{route('languages.destroy',$language)}}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                            onclick="return confirm('¿Seguro que deseas eliminar a este idioma?');">
                            🗑 Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



    @include('parts.footer')
    @include('parts.scripts')
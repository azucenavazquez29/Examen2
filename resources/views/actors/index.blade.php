@include('parts.head')
@include('parts.header')
<div id="cont_principal">
    <div class="container-fluid" id="container_a">
    @include('parts.nav')


<h1 class="text-center my-4 display-5 fw-bold text-success">Actores</h1>

<div class="d-flex justify-content-end mb-3">
    <a href="{{route('actors.create')}}" class="btn btn-success btn-lg shadow">
        ➕ Nuevo Actor
    </a>
</div>

<div class="table-responsive">
    <table class="table table-dark table-striped table-hover table-bordered shadow-lg rounded-3 overflow-hidden align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actors as $actor)
            <tr>
                <td class="fw-bold">{{$actor->first_name}}</td>
                <td class="fw-bold">{{$actor->last_name}}</td>
                <td class="text-center">
                    <a href="{{route('actors.show',$actor)}}" class="btn btn-sm btn-primary me-1 shadow-sm">
                        👁 Ver
                    </a>
                    <a href="{{route('actors.edit',$actor)}}" class="btn btn-sm btn-warning me-1 shadow-sm">
                        ✏️ Editar
                    </a>
                    <form action="{{route('actors.destroy',$actor)}}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                            onclick="return confirm('¿Seguro que deseas eliminar a este actor?');">
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
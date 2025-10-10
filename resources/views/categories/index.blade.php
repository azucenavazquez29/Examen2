@include('parts.head')
@include('parts.header')
<div id="cont_principal">
    <div class="container-fluid" id="container_a">
    @include('parts.nav')


<h1 class="text-center my-4 display-5 fw-bold text-success">Categorias</h1>

<div class="d-flex justify-content-end mb-3">
    <a href="{{route('categories.create')}}" class="btn btn-success btn-lg shadow">
        ➕ Nueva Categoria
    </a>
</div>

<div class="table-responsive">
    <table class="table table-dark table-striped table-hover table-bordered shadow-lg rounded-3 overflow-hidden align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Categoria</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td class="fw-bold">{{$category->name}}</td>
                <td class="text-center">
                    <a href="{{route('categories.show',$category)}}" class="btn btn-sm btn-primary me-1 shadow-sm">
                        👁 Ver
                    </a>
                    <a href="{{route('categories.edit',$category)}}" class="btn btn-sm btn-warning me-1 shadow-sm">
                        ✏️ Editar
                    </a>
                    <form action="{{route('categories.destroy',$category)}}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                            onclick="return confirm('¿Seguro que deseas eliminar a esta catgoria?');">
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
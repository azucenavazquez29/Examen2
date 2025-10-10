@include('parts.head')
@include('parts.header')

<div id="cont_principal">
    <div class="container-fluid" id="container_a">
        @include('parts.nav')

        <h1 class="text-center my-4 display-5 fw-bold text-light">Clientes/Usuarios</h1>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('customers.create') }}" class="btn btn-success btn-lg shadow">
                ➕ Nueva Usuario
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover table-bordered shadow-lg rounded-3 overflow-hidden align-middle">
                <thead class="text-center">
                    <tr>
                        <th>Tienda</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo(Email)</th>
                        <th>Direccion</th>
                        <th>Es Activo?</th>
                        <th>Fecha de creacion</th>
                        <th>Fecha de Actualizacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td class="text-center">{{ $customer->store_id }}</td>
                            <td class="text-center">{{ $customer->first_name }}</td>
                            <td class="text-center">{{ $customer->last_name }}</td>
                            <td class="text-center">{{ $customer->email }}</td>
                            <td class="text-center">{{ $customer->address_id }}</td>
                            <td>
                                @if($customer->active)
                                    <span class="badge bg-success m-1" style="color:white !important;">Activo</span>
                                @else
                                    <span class="badge bg-danger m-1" style="color:white !important;">No Activo</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $customer->create_date }}</td>
                            <td class="text-center">{{ $customer->last_update }}</td>
                            <td class="text-center">
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-primary me-1 shadow-sm" data-bs-toggle="tooltip" title="Ver detalles">
                                    👁 Ver
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning shadow-sm" data-bs-toggle="tooltip" title="Editar Cliente">
                                    ✏️ Editar
                                </a>

                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                       →             @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                                        onclick="return confirm('¿Seguro que deseas eliminar el cliente {{ $customer->first_name }} {{ $customer->last_name }}?');"
                                        data-bs-toggle="tooltip" title="Eliminar Cliente">
                                        🗑 Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay clientes registrados.
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

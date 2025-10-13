@extends('layouts.app')

@section('title', 'Gestión de Tiendas')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-center my-4 display-5 fw-bold" style="color:white !important; font-weight:bolder !important;">Gestión de Tiendas</h2>
                <a href="{{ route('stores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Tienda
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($stores->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Dirección</th>
                                        <th>Ciudad</th>
                                        <th>Gerente</th>
                                        <th>Teléfono</th>
                                        <th>Personal</th>
                                        <th>Clientes</th>
                                        <th>Inventario</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stores as $store)
                                        <tr>
                                            <td><strong>#{{ $store->store_id }}</strong></td>
                                            <td>
                                                <div>{{ $store->address->address }}</div>
                                                @if($store->address->address2)
                                                    <small class="text-muted">{{ $store->address->address2 }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $store->address->city->city }}</div>
                                                <small class="text-muted">{{ $store->address->district }}</small>
                                            </td>
                                            <td>
                                                @if($store->manager)
                                                    <div>{{ $store->manager->first_name }} {{ $store->manager->last_name }}</div>
                                                    <small class="text-muted">{{ $store->manager->email }}</small>
                                                @else
                                                    <span class="badge bg-warning">Sin gerente</span>
                                                @endif
                                            </td>
                                            <td>{{ $store->address->phone }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $store->staff->count() }} empleados
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ $store->customers->count() }} clientes
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $store->inventory->count() }} items
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('stores.show', $store->store_id) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('stores.edit', $store->store_id) }}" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('stores.destroy', $store->store_id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar esta tienda?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $stores->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-store fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay tiendas registradas</h4>
                            <p class="text-muted">Comienza creando tu primera tienda</p>
                            <a href="{{ route('stores.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Crear Tienda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
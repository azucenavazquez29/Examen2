<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sakila-APP</title>
</head>
<body>
    
<div class="row">
                    <div class="col-sm">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark navegacion bg-body-tertiary" style="max-width: 100%;" id="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="letra_logo">
                <span class="tsukitones_diseno_principal">D</span>ark<span class="tsukitones_diseno_principal">M</span>ovies
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(!Session::has('staff_id') && !Session::has('customer_id'))
                <!-- Inicio - Siempre visible -->
                <li class="nav-item navegacion_item">
                    <a class="nav-link active" aria-current="page" href="{{ url('/') }}">
                        <span class="navegacion_item_color">Inicio</span>
                    </a>
                </li>
                @endif
                
                @if(Session::has('staff_id'))
                    <!-- Menú visible solo para empleados autenticados -->
                    <li class="nav-item navegacion_item">
                        <a class="nav-link" href="{{ url('/empleado') }}">
                            <span class="navegacion_item_color">Empleado</span>
                        </a>
                    </li>

                    @if(Session::get('user_role') === 'admin')
                        <!-- Opciones exclusivas de administrador -->
                        <li class="nav-item navegacion_item">
                            <a class="nav-link" href="{{ url('/stats') }}">
                                <span class="navegacion_item_color">Stats</span>
                            </a>
                        </li>

                        <li class="nav-item navegacion_item">
                            <a class="nav-link" href="{{ url('/reportes') }}">
                                <span class="navegacion_item_color">Reportes</span>
                            </a>
                        </li>

                        <li class="nav-item navegacion_item">
                            <a class="nav-link" href="{{ url('/inventory') }}">
                                <span class="navegacion_item_color">Inventario</span>
                            </a>
                        </li>

                        <li class="nav-item navegacion_item">
                            <a class="nav-link" href="{{ url('/films/import') }}">
                                <span class="navegacion_item_color">OMDB</span>
                            </a>
                        </li>

                        <li class="nav-item navegacion_item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="navegacion_item_color">Catálogos</span>
                            </a>
                            <ul class="dropdown-menu variante">
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/films') }}">
                                    <span class="navegacion_item_color">Películas</span>
                                </a></li>
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/actors') }}">Actores</a></li>
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/categories') }}">Categorías</a></li>
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/languages') }}">Idiomas</a></li>
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/customers') }}">Clientes/Usuarios</a></li>
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/stores') }}">Tiendas</a></li>
                                <li><a class="dropdown-item variante_opciones" href="{{ url('/staff') }}">Empleados</a></li>
                            </ul>
                        </li>
                    @endif
                
                @elseif(Session::has('customer_id'))
                    <!-- Menú visible solo para clientes autenticados -->
                    <li class="nav-item navegacion_item">
                        <a class="nav-link" href="{{ route('cliente', ['customer_id' => Session::get('customer_id')]) }}">
                            <span class="navegacion_item_color">Cuenta</span>
                        </a>
                    </li>

                    <li class="nav-item navegacion_item">
                        <a class="nav-link" href="{{ route('catalog') }}">
                            <span class="navegacion_item_color">Catálogo</span>
                        </a>
                    </li>
                
                @else
                    <!-- Usuario NO autenticado - Sin opciones, solo Inicio -->
                @endif
            </ul>

            <div class="nav-contenedor-botones">
                <a href="#" class="nav-boton-formato">
                    <div style="background-color: #181820;"><i class="fas fa-sun" style="color:white;"></i></div>
                </a>
                
                @if(Session::has('staff_id'))
                    <!-- Usuario autenticado - EMPLEADO -->
                    <div class="dropdown">
                        <a href="#" class="nav-boton-formato dropdown-toggle" id="userDropdownStaff" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div><i class="fa-solid fa-user-tie" style="color:white;"></i></div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end variante" aria-labelledby="userDropdownStaff">
                            <li class="px-3 py-2">
                                <small style="color:white !important;" class="text-muted">{{ Session::get('staff_name') }}</small><br>
                                <small style="color:white !important;" class="text-muted">{{ Session::get('staff_email') }}</small><br>
                                <small  style="color:white !important;" class="text-muted">
                                    <strong>Rol:</strong> {{ ucfirst(Session::get('user_role', 'employee')) }}
                                </small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST" id="logoutFormStaff">
                                    @csrf
                                    <button type="submit" class="dropdown-item variante_opciones">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @elseif(Session::has('customer_id'))
                    <!-- Usuario autenticado - CLIENTE -->
                    <div class="dropdown">
                        <a href="#" class="nav-boton-formato dropdown-toggle" id="userDropdownCustomer" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div><i class="fa-solid fa-user-check" style="color:white;"></i></div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end variante" aria-labelledby="userDropdownCustomer">
                            <li class="px-3 py-2">
                                <small class="text-muted">{{ Session::get('customer_name') }}</small><br>
                                <small class="text-muted">{{ Session::get('customer_email') }}</small><br>
                                <small class="text-muted"><strong>Rol:</strong> Cliente</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="{{ route('cliente', ['customer_id' => Session::get('customer_id')]) }}" class="dropdown-item variante_opciones">
                                    <i class="fa-solid fa-user-cog me-2"></i>Mi Perfil
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('auth.cliente.logout') }}" method="POST" id="logoutFormCustomer">
                                    @csrf
                                    <button type="submit" class="dropdown-item variante_opciones">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Botón de login para usuarios no autenticados -->
                    <a href="#" class="nav-boton-formato boton-de-login" 
                       data-bs-toggle="modal" data-bs-target="#modalLogin">
                        <div><i class="fa-solid fa-user" style="color:white;"></i></div>
                    </a>
                @endif
            </div>

            <!-- Buscador -->
            <form class="d-flex" role="search" method="get" action="/buscar">
                <div class="input-buscar-container">
                    <i class="fas fa-search"></i>
                    <input maxlength="40" 
                           style="background-color: rgba(33,36,51,255); border-radius: 5rem !important; border-color:#9295af; border-width: 0.1rem; color:#7f829f;" 
                           class="form-control me-2 input-grueso" 
                           type="search" 
                           placeholder="Buscar película.." 
                           aria-label="Search" 
                           name="pelicula_buscar" />
                    <div class="boton-filtro">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15px" fill="none" viewBox="0 0 24 24" 
                                 stroke-width="1.5" stroke="#a7acd1" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                            </svg>
                            <span>Filtros</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Modal de Login - Solo se muestra si NO hay sesión -->
@if(!Session::has('staff_id') && !Session::has('customer_id'))
<div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cuadro-login">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalTitle">Acceso al Sistema</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Tabs para Empleado / Cliente -->
                <ul class="nav nav-tabs mb-3" id="loginTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" 
                                data-bs-target="#cliente-login" type="button" role="tab">
                            Cliente
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="empleado-tab" data-bs-toggle="tab" 
                                data-bs-target="#empleado-login" type="button" role="tab">
                            Empleado
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="loginTabContent">
                    
                    <!-- Login Cliente -->
                    <div class="tab-pane fade show active" id="cliente-login" role="tabpanel">
                        <form action="{{ route('auth.cliente.login') }}" method="POST" id="loginFormCliente">
                            @csrf
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label label-modal">Correo electrónico</label>
                                <input type="email" name="email" class="form-control input-modal" 
                                       placeholder="correo@gmail.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label label-modal">ID de Dirección (Contraseña)</label>
                                <input type="number" name="password" class="form-control input-modal" 
                                       placeholder="Ingresa tu ID de dirección" required>
                                <small class="text-muted">Tu ID de dirección es tu contraseña temporal</small>
                            </div>
                            
                            <button type="submit" class="btn btn-login-modal w-100 mb-3">
                                Iniciar sesión como Cliente
                            </button>

                            <div class="text-center mb-3">
                                <a href="{{ route('password.request') }}" class="link-register">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>

                            <div class="text-center register-link">
                                ¿No tienes cuenta? 
                                <a href="#" id="showRegisterCliente" class="link-register">Regístrate aquí</a>
                            </div>
                        </form>

                        <!-- Formulario de Registro Cliente (oculto inicialmente) -->
                        <div id="registerFormCliente" style="display: none;">
                            <button class="btn btn-link mb-3 p-0" id="backToLoginCliente">
                                <i class="fa-solid fa-arrow-left me-2"></i>Volver al login
                            </button>
                            
                            <form action="{{ route('auth.cliente.register') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Nombre</label>
                                        <input type="text" name="first_name" class="form-control input-modal" 
                                               placeholder="Juan" maxlength="45" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Apellido</label>
                                        <input type="text" name="last_name" class="form-control input-modal" 
                                               placeholder="Pérez" maxlength="45" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label label-modal">Correo electrónico</label>
                                    <input type="email" name="email" class="form-control input-modal" 
                                           placeholder="correo@gmail.com" maxlength="50" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label label-modal">Dirección</label>
                                    <input type="text" name="address" class="form-control input-modal" 
                                           placeholder="Calle Principal #123" maxlength="50" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Distrito</label>
                                        <input type="text" name="district" class="form-control input-modal" 
                                               placeholder="Centro" maxlength="20" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Código Postal</label>
                                        <input type="text" name="postal_code" class="form-control input-modal" 
                                               placeholder="12345" maxlength="10">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Ciudad</label>
                                        <select name="city_id" class="form-control input-modal" required>
                                            <option value="">Selecciona ciudad</option>
                                            <option value="1">Lethbridge</option>
                                            <option value="2">Woodridge</option>
                                            <option value="312">México</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label label-modal">Teléfono</label>
                                        <input type="tel" name="phone" class="form-control input-modal" 
                                               placeholder="123-456-7890" maxlength="20" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label label-modal">Sucursal</label>
                                    <select name="store_id" class="form-control input-modal" required>
                                        <option value="">Selecciona sucursal</option>
                                        <option value="1">Sucursal 1</option>
                                        <option value="2">Sucursal 2</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-login-modal w-100">
                                    Crear mi cuenta
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Login Empleado -->
                    <div class="tab-pane fade" id="empleado-login" role="tabpanel">
                        <form action="{{ route('auth.login') }}" method="POST" id="loginFormEmpleado">
                            @csrf
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label label-modal">Correo electrónico</label>
                                <input type="email" name="email" class="form-control input-modal" 
                                       placeholder="empleado@sakila.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label label-modal">Contraseña</label>
                                <input type="password" name="password" class="form-control input-modal" 
                                       placeholder="••••••" required>
                            </div>
                            
                            <button type="submit" class="btn btn-login-modal w-100 mb-3">
                                Iniciar sesión como Empleado
                            </button>

                            <div class="text-center register-link">
                                <small class="text-muted">Acceso solo para personal autorizado</small>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle entre login y registro de cliente
document.addEventListener('DOMContentLoaded', function() {
    const showRegisterBtn = document.getElementById('showRegisterCliente');
    const backToLoginBtn = document.getElementById('backToLoginCliente');
    const loginFormCliente = document.getElementById('loginFormCliente');
    const registerFormCliente = document.getElementById('registerFormCliente');
    
    if (showRegisterBtn) {
        showRegisterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginFormCliente.style.display = 'none';
            registerFormCliente.style.display = 'block';
            document.getElementById('modalTitle').textContent = 'Registro de Cliente';
        });
    }
    
    if (backToLoginBtn) {
        backToLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            registerFormCliente.style.display = 'none';
            loginFormCliente.style.display = 'block';
            document.getElementById('modalTitle').textContent = 'Acceso al Sistema';
        });
    }
});

// Si hay errores de validación o se solicita mostrar modal, reabrirlo
@if($errors->any() || session('show_modal'))
    window.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalLogin'));
        modal.show();
    });
@endif
</script>
@endif

                    </div>
                </div>
</body>
</html>
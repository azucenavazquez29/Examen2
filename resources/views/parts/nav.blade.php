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
                                <a class="navbar-brand" href="#"><span class="letra_logo"><span class="tsukitones_diseno_principal">D</span>ark<span class="tsukitones_diseno_principal">M</span>ovies</span></a>
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                                    <li class="nav-item navegacion_item">
                                        <a class="nav-link active" aria-current="page" href="{{ url('/') }}"><span class="navegacion_item_color">Inicio</span></a>
                                    </li>
                                    
                                    <li class="nav-item navegacion_item">
                                        <a class="nav-link" href="{{ url('/cliente') }}"><span class="navegacion_item_color">User</span></a>
                                    </li>

                                    <li class="nav-item navegacion_item">
                                        <a class="nav-link" href="{{ url('/cliente') }}"><span class="navegacion_item_color">Empleado</span></a>
                                    </li>

                                    <li class="nav-item navegacion_item">
                                        <a class="nav-link" href="{{ url('/stats') }}"><span class="navegacion_item_color">Stats</span></a>
                                    </li>

                                    <li class="nav-item navegacion_item">
                                        <a class="nav-link" href="{{ url('/reportes') }}"><span class="navegacion_item_color">Reportes</span></a>
                                    </li>

                                    <li class="nav-item navegacion_item">
                                        <a class="nav-link" href="{{ url('/films/import') }}"><span class="navegacion_item_color">OMDB</span></a>
                                    </li>

                                    <li class="nav-item navegacion_item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="navegacion_item_color">Catalogos</span>
                                        </a>
                                        <ul class="dropdown-menu variante">
                                            <li><a class="dropdown-item variante_opciones" href="{{ url('/films') }}"><span class="navegacion_item_color">Peliculas</span></a></li>
                                            <li><a class="dropdown-item variante_opciones" href="{{ url('/actors') }}">Actores</a></li>
                                            <li><a class="dropdown-item variante_opciones" href="{{ url('/categories') }}">Categorias</a></li>
                                            <li><a class="dropdown-item variante_opciones" href="{{ url('/languages') }}">Idiomas</a></li>
                                            <li><a class="dropdown-item variante_opciones" href="{{ url('/customers') }}">Clientes/Usuarios</a></li>
                                        </ul>
                                    </li>

                                    
                                </ul>

                            <div class="nav-contenedor-botones">
                                    <a href="#"  class="nav-boton-formato">
                                        <div style="background-color: #181820;"><i class="fas fa-sun" style="color:white;"></i></div>
                                    </a>
                                    <a href="#" class="nav-boton-formato"
                                    class="boton-de-login" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalLogin">
                                    <div><i class="fa-solid fa-user" style="color:white;" ></i></div>
                                    </a>
                            </div>

                            <!-- Ventana modal de login -->
                                    <div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content cuadro-login">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title" id="modalTitle">Iniciar Sesión</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <!-- Formulario de Login -->
                                                    <div id="loginForm" class="fade-section">
                                                        <div class="mb-3">
                                                            <label class="form-label label-modal">Correo electrónico</label>
                                                            <input type="email" class="form-control input-modal" placeholder="correo@gmail.com">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label label-modal">Contraseña</label>
                                                            <input type="password" class="form-control input-modal" placeholder="••••••">
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input check-modal" type="checkbox" id="rememberMe">
                                                                <label class="form-check-label text-modal" for="rememberMe">
                                                                    Recuérdame
                                                                </label>
                                                            </div>
                                                            <a href="#" class="link-forgot">Olvidé mi contraseña</a>
                                                        </div>
                                                        
                                                        <div class="cloudflare-box">
                                                            <div class="form-check m-0">
                                                                <input class="form-check-input check-modal" type="checkbox" id="humanVerify">
                                                                <label class="form-check-label text-modal" for="humanVerify">
                                                                    Verifica que eres un ser humano
                                                                </label>
                                                            </div>
                                                            <div class="cloudflare-icon">
                                                                🛡️
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-login-modal w-100 mb-3">Iniciar sesión</button>

                                                        <div class="divider-modal">
                                                            <span>o</span>
                                                        </div>

                                                        <button class="btn btn-social-modal w-100 mb-2">
                                                            Entrar con tu cuenta Google
                                                        </button>
                                                        <button class="btn btn-social-modal w-100 mb-3">
                                                            Entrar con tu cuenta Discord
                                                        </button>

                                                        <div class="text-center register-link">
                                                            ¿Aún no tienes una cuenta? <a href="#" id="showRegister" class="link-register">Registrarme</a>
                                                        </div>
                                                    </div>

                                                    <!-- Formulario de Registro -->
                                                    <div id="registerForm" class="fade-section" style="display: none;">
                                                        <div class="mb-3">
                                                            <label class="form-label label-modal">Nombre de usuario</label>
                                                            <input type="text" class="form-control input-modal" placeholder="Ingresa usuario">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label label-modal">Correo electrónico</label>
                                                            <input type="email" class="form-control input-modal" placeholder="Ingresa correo">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label label-modal">Contraseña</label>
                                                            <input type="password" class="form-control input-modal" placeholder="••••••">
                                                        </div>

                                                        <div class="cloudflare-box">
                                                            <div class="form-check m-0">
                                                                <input class="form-check-input check-modal" type="checkbox" id="humanVerifyReg">
                                                                <label class="form-check-label text-modal" for="humanVerifyReg">
                                                                    Verifica que eres un ser humano
                                                                </label>
                                                            </div>
                                                            <div class="cloudflare-icon">
                                                                🛡️
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-login-modal w-100 mb-3">Crear mi cuenta</button>

                                                        <div class="text-center register-link">
                                                            ¿Ya tienes una cuenta? <a href="#" id="showLogin" class="link-register">Iniciar Sesión</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

<!--
                                    

                                <form class="d-flex" role="search">
                                    <input style="border-color: rgb(94, 184, 211); background-color:rgb(212, 208, 208); border-radius: 5rem !important" class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search"/>
                                    <button style="border-radius: 5rem !important" class="btn btn-outline-success boton_buscar" type="submit">Buscar</button>
                                </form>

-->

                                <form class="d-flex" role="search" method="get" action="/buscar">
                                    <div class="input-buscar-container">
                                        <i class="fas fa-search"></i>
                                        <input maxlength="40" style="background-color: rgba(33,36,51,255); border-radius: 5rem !important; border-color:#9295af ; border-width: 0.1rem; color:#7f829f;" class="form-control me-2 input-grueso" type="search" placeholder="Buscar anime.." aria-label="Search" name="anime_buscar" />
                                        <div class="boton-filtro">
                                            <a href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg"  width="15px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#a7acd1" class="size-6">
                                                    <path stroke-linecap="round"  stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                                                </svg>
                                                <span >Filtros</span>
                                            </a>
                                        </div>
                                    </div>
                                </form>


                                </div>
                            </div>
                        </nav>

                    </div>
                </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                                        <a class="nav-link" href="#"><span class="navegacion_item_color">Pefil</span></a>
                                    </li>

                                    <li class="nav-item navegacion_item">
                                        <a class="nav-link" href="{{ url('/films') }}"><span class="navegacion_item_color">Peliculas</span></a>
                                    </li>

                                    <li class="nav-item navegacion_item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="navegacion_item_color">Listas</span>
                                        </a>
                                        <ul class="dropdown-menu variante">
                                            <li><a class="dropdown-item variante_opciones" href="{{ url('/actors') }}">Actores</a></li>
                                        </ul>
                                    </li>

                                    
                                </ul>
                                <form class="d-flex" role="search">
                                    <input style="border-color: rgb(94, 184, 211); background-color:rgb(212, 208, 208); border-radius: 5rem !important" class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search"/>
                                    <button style="border-radius: 5rem !important" class="btn btn-outline-success boton_buscar" type="submit">Buscar</button>
                                </form>
                                </div>
                            </div>
                        </nav>

                    </div>
                </div>
</body>
</html>
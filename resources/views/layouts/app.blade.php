<!DOCTYPE html>
<html lang="es">
<head>
    @include('parts.head')
</head>
<body>
    @include('parts.header')

    <div id="cont_principal">
        <div class="container-fluid" id="container_a">

            @include('parts.nav')

            {{-- Aquí va el contenido dinámico de cada página --}}
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    @include('parts.footer')
    @include('parts.scripts')
</body>
</html>

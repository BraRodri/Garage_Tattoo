<x-app-layout>

    @section('pagina')
        <?=$PaginaTitulo?>
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1><?=$PaginaTitulo?></h1>
        </div>

        <!-- página estandard EMPRESA -->
        <div class="row">

            <div class="col-md-3">

                <div class="list-group">
                    <a href="{{ route('mi.cuenta') }}" class="list-group-item list-group-item-action {{ ! Route::is('mi.cuenta') ?: 'active' }}">Mi Cuenta</a>
                    <a href="{{ route('mis.pedidos') }}" class="list-group-item list-group-item-action {{ ! Route::is('mis.pedidos') ?: 'active' }}">Estado Pedidos</a>
                    <a href="{{ route('mis.datos') }}" class="list-group-item list-group-item-action {{ ! Route::is('mis.datos') ?: 'active' }}">Detalles de la Cuenta</a>
                    <a href="{{ route('mis.puntos') }}" class="list-group-item list-group-item-action {{ ! Route::is('mis.puntos') ?: 'active' }}">Mis Puntos</a>
                    <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">Salir</a>
                </div>

            </div>

            <div class="col-md-9 cont-contenedor">
                <div class="mi-cuenta-paginas">
                    <p> Hola <strong>{{ Auth::guard('client')->user()->business_name }}</strong> (¿no eres <strong>{{ Auth::guard('client')->user()->business_name }}</strong>? <a href="{{ route('logout') }}">Cerrar Sesión</a>)</p>
                    <p>Desde el escritorio de tu cuenta puedes ver tus pedidos recientes, gestionar tus direcciones de envío y facturación y editar tu contraseña y los detalles de tu cuenta.</p>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mi-cuenta-paginas-item text-center">
                                <a href="{{ route('mis.pedidos') }}">
                                    <img src="{{ asset('images/icon-mis-pedidos.svg') }}" width="60" height="60" alt="mis pedidos">
                                    <h2>Estado Pedidos</h2>
                                </a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mi-cuenta-paginas-item text-center">
                                <a href="{{ route('mis.datos') }}">
                                <img src="{{ asset('images/icon-mi-cuenta.svg') }}" width="60" height="60" alt="mi cuenta">
                                    <h2>Detalles de la Cuenta</h2>
                                </a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mi-cuenta-paginas-item text-center">
                                <a href="{{ route('mis.puntos') }}">
                                <img src="{{ asset('images/mis-puntos.svg') }}" width="60" height="60" alt="mis puntos">
                                    <h2>Mis Puntos</h2>
                                </a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mi-cuenta-paginas-item text-center">
                                <a href="{{ route('logout') }}">
                                <img src="{{ asset('images/icon-cerrar-sesion.svg') }}" width="60" height="60" alt="cerrar sesión">
                                    <h2>Salir</h2>
                                </a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

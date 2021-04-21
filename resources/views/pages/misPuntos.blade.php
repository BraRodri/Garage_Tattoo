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
                    <p>Conoce tus puntos acumulados de las compras realizadas anteriormente. Tener presente que podrás cangear tus puntos con una cantidad mínima de <strong>5.000</strong> puntos.</p>
                    <div class="mis-puntos">
                        <div class="mis-puntos-total">
                            <h2 class="text-center">7.620</h2>
                            <p class="text-center">Total puntos </br> acumulados en el mes</p>
                        </div>

                        <div>
                            <select id="inputState" class="form-control">
                                <option selected="">- Mes -</option>
                                <option>Enero 2021</option>
                                <option>Febrero 2021</option>
                                <option>Marzo 2021</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                    <table class="table bg-white table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                            <th scope="col">FECHA</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col">MONTO</th>
                            <th scope="col">PUNTOS</th>
                            <th scope="col">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th scope="row">03/02/2021</th>
                            <td>Compra en línea</td>
                            <td>$50.990</td>
                            <td>5010</td>
                            <td><a href="#" class="btn btn-warning">Cangear <i class="fa fa-plus-circle"></i></a> </td>
                            </tr>
                            <tr>
                            <th scope="row">22/02/2021</th>
                            <td>Compra en línea</td>
                            <td>$17.670</td>
                            <td>1080</td>
                            <td><a href="#" class="btn btn-warning">Cangear <i class="fa fa-plus-circle"></i></a> </td>
                            </tr>
                            <tr>
                            <th scope="row">27/03/2021</th>
                            <td>Compra en línea</td>
                            <td>$12.840</td>
                            <td>1030</td>
                            <td><a href="#" class="btn btn-warning">Cangear <i class="fa fa-plus-circle"></i></a> </td>
                            </tr>
                            <tr>
                            <th scope="row">21/03/2021</th>
                            <td>Compra en línea</td>
                            <td>$5.990</td>
                            <td>600</td>
                            <td><a href="#" class="btn btn-warning">Cangear <i class="fa fa-plus-circle"></i></a> </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                </div>
            </div>

        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

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
                    <p>Revisa el Estado de tus Pedidos e historial de todas tus compras.</p>
                <div class="table-responsive-sm">
                    <table class="table bg-white table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                            <th scope="col">Nº PEDIDO</th>
                            <th scope="col">FECHA</th>
                            <th scope="col">ESTADO</th>
                            <th scope="col">TIPO DESPACHO</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th scope="row">0349034</th>
                            <td>13/03/2021</td>
                            <td><div class="proceso1">Entregado</div></td>
                            <td>Región de Valparaíso</td>
                            <td>$50.990</td>
                            <td><a href="#" class="btn btn-warning"><i class="fa fa-download"></i></a> <a href="#" class="btn btn-warning"><i class="fa fa-trash-o"></i></a></td>
                            </tr>
                            <tr>
                            <th scope="row">4059405940</th>
                            <td>15/03/2021</td>
                            <td><div class="proceso2">Cencelado</div></td>
                            <td>Región Metropolitana R.M.</td>
                            <td>$17.670</td>
                            <td><a href="#" class="btn btn-warning"><i class="fa fa-download"></i></a> <a href="#" class="btn btn-warning"><i class="fa fa-trash-o"></i></a></td>
                            </tr>
                            <tr>
                            <th scope="row">445454</th>
                            <td>19/03/2021</td>
                            <td><div class="proceso3">En Tránsito</div></td>
                            <td>Región Metropolitana R.M.</td>
                            <td>$12.840</td>
                            <td><a href="#" class="btn btn-warning"><i class="fa fa-download"></i></a> <a href="#" class="btn btn-warning"><i class="fa fa-trash-o"></i></a></td>
                            </tr>
                            <tr>
                            <th scope="row">9845983</th>
                            <td>21/03/2021</td>
                            <td><div class="proceso4">Preparando Pedido</div></td>
                            <td>Región Metropolitana R.M.</td>
                            <td>$5.990</td>
                            <td><a href="#" class="btn btn-warning"><i class="fa fa-download"></i></a> <a href="#" class="btn btn-warning"><i class="fa fa-trash-o"></i></a></td>
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

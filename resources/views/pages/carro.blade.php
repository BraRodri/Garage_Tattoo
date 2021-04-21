@php
    function convertirValor($precio)
    {
        $valor = number_format($precio, 0, ',', '.');
        return $valor;
    }
@endphp

<x-app-layout>

    @section('meta')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endsection

    @section('pagina')
        <?=$PaginaTitulo?>
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1><?=$PaginaTitulo?></h1>
        </div>

        @if (Session::has('error'))
            @if (Session::get('error') == 'eliminado')

                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Producto Eliminado!</strong> Sigue comprando.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @endif
                    @if (Session::get('error') == 'actualizado')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Productos Actualizados!</strong> Sigue comprando.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

            @endif
        @endif

        @if (Cart::getTotalQuantity()>0)

            <!-- página estandard CARRO -->
            <div class="cont-contenedor">

                <div class="r-carro">
                    <h3><i class="fa fa-shopping-bag"></i> <strong>({{  Cart::getTotalQuantity() }})</strong> Productos en mi Carro</h3>
                </div>

                <!-- ALERTAS QUE INFORAN SI UN PRODUCTO ESTÁ O NO DISPONIBLE PARA DESPACHO A REGIOENS U OTRA CONDICIÓN -->


                <div class="row">
                    <div class="col-md-12 col-lg-4 mt-3">

                        <p>1. Revisa tus Productos</p>

                        <!-- PRODUCTOS TABLA -->
                        <x-tabla-productos-carro></x-tabla-productos-carro>

                        <!-- CUPON DE DESCUENTO -->
                        <x-cupon-descuento></x-cupon-descuento>

                    </div>

                    <div class="col-md-12 col-lg-4 mt-3">

                        <p>2. Retiro o Envío</p>

                        <div class="r-carro-total">
                            <ul>
                                <h2>Retiro o Envío</h2>

                                <li class="d-flex justify-content-between">
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" value="" name="defaultCheck1">
                                      <label class="form-check-label" for="defaultCheck1">
                                       Retiro en Tienda</strong>
                                      </label>
                                    </div>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" value="" name="defaultCheck1">
                                      <label class="form-check-label" for="defaultCheck1">
                                      Despacho Express en el día Santiago (pedidos hasta las 14:00Hrs): $8.990</strong>
                                      </label>
                                    </div>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" value="" name="defaultCheck1">
                                      <label class="form-check-label" for="defaultCheck1">
                                      Despacho Garage Tatto 24Hrs! Santiago (Excluye, Buin, Paine, Lampa, Talagante, Melipilla): $3.990</strong>
                                      </label>
                                    </div>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" value="" name="defaultCheck1">
                                      <label class="form-check-label" for="defaultCheck1">
                                      Despacho Regiones (bulto pequeño): $8.990</strong>
                                      </label>
                                    </div>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" value="" name="defaultCheck1">
                                      <label class="form-check-label" for="defaultCheck1">
                                      Despacho Region Por Pagar (Bultos grande)</strong>
                                      </label>
                                    </div>
                                </li>

                                <hr>

                                <h2>Dirección de envio.</h2>

                                <li class="d-flex justify-content-between">
                                    <div class="form-check">
                                      <input class="form-check-input" type="radio" value="" name="direccion_envio">
                                      <label class="form-check-label" for="defaultCheck1">
                                      Utilizar mi direción principal.</strong>
                                      </label>
                                    </div>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <div class="form-check">
                                       <input class="form-check-input" type="radio" value="" name="direccion_envio">
                                       <label class="form-check-label" for="defaultCheck1">
                                         Otra Dirección: (seleccione según Alias)
                                       </label>
                                     </div>
                                 </li>

                                <li>
                                    <div class="form-group col-md-12">
                                       <select id="inputState" class="form-control">
                                         <option selected="">- Direcciones -</option>
                                         <option>(casa 2) Mario Lincon Nº124 - Región Metropolitana - Santiago, San Bernardo</option>
                                         <option>(oficina) Apoquindo 4400 - Región Metropolitana - Santiago, Las Condes</option>
                                       </select>
                                     </div>
                                     <div class="text-center">
                                       <a href="mis-datos-cuenta.php" class="btn btn-warning">Agregar nueva dirección</a>
                                     </div>
                                 </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <hr>

            <div class="botones-carros d-flex justify-content-between">
                <a href="{{ route('allProducts') }}" class="btn btn-dark"><i class="fa fa-long-arrow-left"></i> Continuar Comprando</a>
                <div class="btn-comprar-cotizar">
                <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#sinregistro">Comprar sin registro <i class="fa fa-long-arrow-right"></i></a>
                <a href="#" class="btn btn-danger">Comprar <i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>

            @else
            <x-carro-vacio></x-carro-vacio>

        @endif

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

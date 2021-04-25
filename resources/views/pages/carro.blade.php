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

                            <form action="datos.php" method="POST" id="formFinalizarCompra">
                                @csrf

                            <p>2. Retiro o Envío</p>

                            <div class="r-carro-total">
                                <ul>
                                    <h2>Retiro o Envío</h2>

                                    @if($configuration->office_shipping_active == 1)
                                        <li class="d-flex justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="Retiro en Tienda" id="retiro_envio" name="retiro_envio" required>
                                                <label class="form-check-label" for="">
                                                    Retiro en Tienda
                                                </label>
                                            </div>
                                        </li>
                                    @endif

                                    @if(count($config_dispatchs)>0)

                                        @foreach($config_dispatchs as $key => $dispatch)
                                            <li class="d-flex justify-content-between">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="{{$dispatch->description}}" id="retiro_envio" name="retiro_envio">
                                                    <label class="form-check-label" for="">
                                                        {{$dispatch->description}}: ${{convertirValor($dispatch->price)}}</strong>
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach

                                    @endif

                                    <hr>

                                    <h2>Dirección de envio.</h2>

                                    @guest('client')
                                        <li class="d-flex justify-content-between">
                                            <h6 class="text-white">Inicia Sesión o Compra sin Registro.</h6>
                                        </li>

                                        @else
                                        <li class="d-flex justify-content-between">
                                            <div class="form-check">
                                            <input class="form-check-input" type="radio" value="" name="direccion_envio">
                                            <label class="form-check-label" for="defaultCheck1">
                                                Mi Dirección actual: {{$info_client->address}}, {{$info_client->location->description}}. Región {{$info_client->region->description}}</strong>
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
                                                <select id="inputState" class="form-control" required="">
                                                    <option value="" selected="">- Direcciones -</option>

                                                    @if(count($address_clients)>0)
                                                        @foreach($address_clients as $key => $value_add)
                                                            <option value="{{$value_add->id}}">({{$value_add->alias}}) {{$value_add->address}} - Región {{$value_add->region->description}} - {{$value_add->province->description}}, {{$value_add->location->description}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="text-center">
                                                <a href="{{ route('mis.datos') }}" class="btn btn-warning">Agregar nueva dirección</a>
                                            </div>
                                        </li>

                                    @endguest

                                </ul>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-4 mt-3">

                            <p>3. Pago , Documento y Resumen</p>

                            <x-cart-tipo-documento></x-cart-tipo-documento>

                            <div class="r-carro-total">
                                <ul>
                                   <h2>Forma de Pago</h2>

                                    <li class="d-flex justify-content-between">
                                        <div class="form-check">
                                           <input class="form-check-input" type="radio" value="" id="defaultCheck1">
                                           <label class="form-check-label" for="defaultCheck1">
                                             <i class="fa fa-credit-card"></i> Tarjeta Débito
                                           </label>
                                         </div>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <div class="form-check">
                                           <input class="form-check-input" type="radio" value="" id="defaultCheck1">
                                           <label class="form-check-label" for="defaultCheck1">
                                             <i class="fa fa-cc-visa"></i> / <i class="fa fa-cc-mastercard"></i> Tarjeta Crédito
                                           </label>
                                         </div>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <div class="form-check">
                                           <input class="form-check-input" type="radio" value="" id="defaultCheck1">
                                           <label class="form-check-label" for="defaultCheck1">
                                             <i class="fa fa-university"></i> Transferencia Bancaria
                                           </label>
                                         </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="r-carro-total">
                                <ul>
                                   <h2>Resumen</h2>

                                    <li class="d-flex justify-content-between">
                                        <p>Subtotal</p>
                                        <p><strong>${{ convertirValor(Cart::getSubTotal()) }}</strong></p>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <p>Despacho: Regiones (bulto pequeño)</p>
                                        <p><strong>$8.990</strong></p>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <p>Puntos: acumulas con esta compra</p>
                                        <p><strong>900 pts.</strong></p>
                                    </li>
                                    <hr>
                                    <li class="d-flex justify-content-between">
                                        <p><strong>TOTAL</strong></p>
                                        <p><strong>${{ convertirValor(Cart::getTotal()) }}</strong></p>
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
                        @guest('client')
                            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#sinregistro">Comprar sin registro <i class="fa fa-long-arrow-right"></i></a>

                            @else
                            <button type="submit" class="btn btn-danger">Comprar <i class="fa fa-long-arrow-right"></i></button>
                        @endguest
                    </div>
                </div>

            </form>

            @else
            <x-carro-vacio></x-carro-vacio>

        @endif

    </div>
    <x-slot name="js">

        <script>
            var divFactura = $('#divFormFactura');
            divFactura.hide();

            $(".tipo_documento_1").click(function(){
                var valor = $(this).val();
                if(valor === 'Factura'){
                    divFactura.show();
                    $("#txt_razon_social").attr("required", true);
                    $("#txt_rut").attr("required", true);
                    $("#txt_giro").attr("required", true);
                } else {
                    divFactura.hide();
                    $("#txt_razon_social").attr("required", false);
                    $("#txt_rut").attr("required", false);
                    $("#txt_giro").attr("required", false);
                }
            });
        </script>

    </x-slot>
</x-app-layout>

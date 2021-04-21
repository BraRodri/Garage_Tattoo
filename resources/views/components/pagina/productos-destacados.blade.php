<!-- Operaciones -->
@php
    function convertirValor($precio){
        $valor = number_format($precio, 0, ',', '.');
        return $valor;
    }

    function obtenerPorcentajeDescuento($precio_normal, $precio_oferta){
        $valor_1 = (float) $precio_normal;
        $valor_2 = (float) $precio_oferta;
        $total = round( 100 - ( $precio_oferta / $precio_normal * 100 ), 0 );
        return $total;
    }
@endphp

<div class="row mb-5">

    @if(count($destacados)>0)

        @foreach($destacados as $key => $value)

            <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="sv-producto-mod">
                        <div class="sv-producto-imagen">

                            @if (count($value->galleries) >0)
                                <img src="{{ asset('/files/productsGalleries/img/' . $value->galleries[0]->image) }}" class="img-fluid sv-producto-imagen" alt="."></a>
                            @else
                                <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid sv-producto-imagen " alt="."></a>
                            @endif

                            <div class="sv-producto-mensaje">
                                <div class="sv-producto-ver">
                                    <a href="#" data-toggle="modal" data-target="#vista-previa-producto-{{$value->id}}">VISTA RÁPIDA</a>
                                </div>
                            </div>
                        </div>

                    @foreach($value->categories as $key => $category)
                        @if($category->category->level == 1)
                            <p>{{$category->category->title}}</p>
                        @endif
                    @endforeach
                    <h2 class="text-center"> {{ $value->title }} </h2>

                    <div class="precio-boton d-flex justify-content-center">
                        @if($value->offer_price == '0')

                            @if($value->normal_price != 0)
                                <!-- precio normal -->
                                <div class="sv-precio-normal text-center">
                                    <h3>${{ convertirValor($value->normal_price) }}</h2>
                                </div>
                            @endif

                            @else
                                <!-- precio oferta -->
                                <div class="sv-precio-oferta text-center">
                                    <h4>${{ convertirValor($value->offer_price) }} <span>/ ${{ convertirValor($value->normal_price) }}</span></h4>
                                    <div class="sv-oferta-ticket">
                                        <p><span>-{{ obtenerPorcentajeDescuento($value->normal_price, $value->offer_price) }}%</span></p>
                                    </div>
                                </div>
                        @endif
                    </div>

                    <div class="sv-producto-agregar sv-p-centrar">
                        @if ($value->stock > 0)
                            @php
                                 $agregado = 0;
                            @endphp
                            @foreach (Cart::getContent() as $detail)

                                @if ($detail->attributes['sku'] == $value->sku)
                                    @php
                                        $agregado = 1;
                                    @endphp
                                @endif

                            @endforeach

                            @if ($agregado)
                                <a href="{{route('mi.carro')}}" class="btn btn-dark">PRODUCTO AGREGADO</a>
                                @else
                                <form action="{{ route('cart_details.store') }}" method="post" class="">
                                    @csrf
                                    <input type="text" name="products_id" value="{{ $value->id }}" hidden />
                                    <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1" hidden>
                                    <input type="submit" class="btn btn-dark" value="AGREGAR AL CARRO">
                                </form>
                            @endif

                                @else
                                <a href="{{ route('detalle.producto', $value->slug) }}" class="btn btn-dark">VER DETALLES</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MODAL DE PRODUCTOS VISTA RÁPIDA ** SOLAMENTE PARA PRODUCTOS DESTACADOS -->
            <div class="modal fade" id="vista-previa-producto-{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="vista-previa-producto-{{$value->id}}" aria-hidden="true">

                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Producto Destacado</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body cont-contenedor">
                            <!-- VISTA PREVIA DETALLE PRODUCTO -->
                            <div class="row">

                                <div class="col-md-6 mb-2">
                                    <!-- FOTO SIN ZOOM DETALLE -->
                                    <div>
                                        @if (count($value->galleries) >0)
                                            <img src="{{ asset('/files/productsGalleries/img/' . $value->galleries[0]->image) }}" class="img-fluid" alt="."></a>
                                        @else
                                            <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid" alt="."></a>
                                        @endif
                                    </div>
                                    <!-- Imagen producto detalle -->
                                </div>

                                <div class="col-md-6">
                                    <div class="product-dtl">
                                        <div class="product-info">
                                            <div class="product-name mb-3">
                                                {{$value->title_producto}}
                                            </div>
                                            <p><strong>COD:</strong> {{$value->sku}}</p>
                                            <p><strong>DISPONIBILIDAD:</strong> {{$value->stock}} Disponibles</p>

                                            @if($value->offer_price == '0')

                                                @if($value->normal_price != 0)
                                                    <!-- precio normal -->
                                                    <div class="product-price-normal">
                                                        <span>${{ convertirValor($value->normal_price) }}</span>
                                                    </div>
                                                @endif

                                                @else
                                                    <!-- oferta -->
                                                    <div class="product-price-discount">
                                                        <p>-{{ obtenerPorcentajeDescuento($value->normal_price, $value->offer_price) }}%</p>
                                                        <span>${{ convertirValor($value->offer_price) }}</span><span class="line-through">${{ convertirValor($value->normal_price) }}</span>
                                                    </div>
                                            @endif

                                        </div>

                                            <div class="ws-productos-tipo-entrega">
                                                <h2>TIPO ENTREGA</h2>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <img src="images/icon-domicilio.svg" alt="domicilio">
                                                        <p>Despacho a Domicilio</p>
                                                        @if($value->shipping_active == 1)
                                                            <p><span>Disponible</span></p>
                                                            @else
                                                            <p><strong>No Disponible</strong></p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        <img src="images/icon-local.svg" alt="retiro en tienda">
                                                        <p>Retiro en Tienda</p>
                                                        @if($value->office_shipping_active == 1)
                                                            <p><span>Disponible</span></p>
                                                            @else
                                                            <p><strong>No Disponible</strong></p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-count">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        @if ($value->stock > 0)

                                                            @php
                                                                $agregado = 0;
                                                            @endphp
                                                            @foreach (Cart::getContent() as $detail)

                                                                @if ($detail->attributes['sku'] == $value->sku)
                                                                    @php
                                                                        $agregado = 1;
                                                                    @endphp
                                                                @endif

                                                            @endforeach

                                                            @if ($agregado)
                                                                <h6 class="mt-1">Este producto ya se encuentro agregado en el carrito.</h6>
                                                                <a href="{{route('mi.carro')}}" class="btn btn-warning mb-3">VER CARRITO</a>
                                                            @else
                                                                <label for="size">Cantidad:</label>
                                                                <form action="{{ route('cart_details.store') }}" method="post" class="display-flex mb-4">
                                                                    @csrf
                                                                    <input type="text" name="products_id" value="{{ $value->id }}" hidden />
                                                                    <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1">
                                                                    <input type="submit" class="btn btn-warning" value="AGREGAR AL CARRO">
                                                                </form>
                                                            @endif

                                                        @else
                                                        <a href="#" class="btn btn-danger mt-2 mb-3">PRODUCTO AGOTADO</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- FIN MODAL -->

        @endforeach

    @endif

</div>

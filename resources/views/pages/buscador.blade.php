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

<x-app-layout>

    @section('pagina')
        <?=$PaginaTitulo?>
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1><?=$PaginaTitulo?></h1>
        </div>

        <div class="resultado-busqueda">
            <h2>Resultado Búsqueda: <strong>"{{$data}}"</strong></h2>
        </div>

        <div class="row">

            @if($type)

                @if(!$resultado->isEmpty())

                    @foreach($resultado as $key => $value)

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="sv-producto-mod">
                            <a href="{{ route('detalle.producto', $value) }}">
                                @if (count($value->galleries) >0)
                                    <img src="{{ asset('/files/productsGalleries/img/' . $value->galleries[0]->image) }}" class="img-fluid " alt=".">
                                    @else
                                    <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid " alt=".">
                                @endif
                            </a>
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

                                @if($value->attribute_active == 0)

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
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="number" class="form-control" name="quantity" id="quantity" min="1" max="{{$value->stock}}" value="1" style="width: 60px">
                                                    </div>
                                                    <div class="col">
                                                        <input type="submit" class="btn btn-dark" value="AGREGAR AL CARRO">
                                                    </div>
                                                </div>
                                                <input type="text" name="products_id" value="{{ $value->id }}" hidden />
                                            </form>
                                        @endif

                                            @else
                                            <a href="{{ route('detalle.producto', $value->slug) }}" class="btn btn-dark">VER DETALLES</a>
                                    @endif

                                    @else
                                    <a href="{{ route('detalle.producto', $value->slug) }}" class="btn btn-dark">AGREGAR AL CARRO</a>

                                @endif
                            </div>
                        </div>
                    </div>

                    @endforeach

                    @else
                    <div class="col-lg-12">
                        <div class="resultado-productos text-center">
                            <h6>No se encontraron resultados.</h6>
                        </div>
                    </div>

                @endif

                @else

                    @if($resultado)

                        @foreach($resultado as $key => $value)

                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <div class="sv-producto-mod">
                                    <a href="{{ route('detalle.producto', $value->slug_producto) }}">

                                        @php
                                            $existe = false;
                                        @endphp
                                        @foreach($images_product as $key => $value_image)
                                            @foreach($value_image as $key => $value_final)
                                                @if($value_final->products_id == $value->product_id && $value_final->position == 1)
                                                    <img src="{{ asset('/files/productsGalleries/img/' . $value_final->image) }}" class="img-fluid " alt=".">
                                                    @php
                                                        $existe = true;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endforeach

                                        @if(!$existe)
                                            <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid " alt=".">
                                        @endif

                                    </a>

                                    <p>{{$value->title_category}}</p>
                                    <h2 class="text-center"> {{$value->title_product}} </h2>

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

                                        @if($value->attribute_active == 0)

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
                                                        <div class="row">
                                                            <div class="col">
                                                                <input type="number" class="form-control" name="quantity" id="quantity" min="1" max="{{$value->stock}}" value="1" style="width: 60px">
                                                            </div>
                                                            <div class="col">
                                                                <input type="submit" class="btn btn-dark" value="AGREGAR AL CARRO">
                                                            </div>
                                                        </div>
                                                        <input type="text" name="products_id" value="{{ $value->product_id }}" hidden />
                                                    </form>
                                                @endif
                                            @endif

                                            @else
                                            <a href="{{ route('detalle.producto', $value->slug_producto) }}" class="btn btn-dark">AGREGAR AL CARRO</a>

                                        @endif

                                    </div>
                                </div>
                            </div>

                        @endforeach

                        @else
                        <div class="col-lg-12">
                            <div class="resultado-productos text-center">
                                <h6>No se encontraron resultados.</h6>
                            </div>
                        </div>

                    @endif


            @endif

        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

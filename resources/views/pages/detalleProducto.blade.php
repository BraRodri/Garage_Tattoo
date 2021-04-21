@php
function convertirValor($precio)
{
    $valor = number_format($precio, 0, ',', '.');
    return $valor;
}

function obtenerPorcentajeDescuento($precio_normal, $precio_oferta)
{
    $valor_1 = (float) $precio_normal;
    $valor_2 = (float) $precio_oferta;
    $total = round(100 - ($precio_oferta / $precio_normal) * 100, 0);
    return $total;
}
@endphp

<x-app-layout>

    @section('pagina')
        {{$product->title}}
    @endsection

    <div class="container">

        <div class="titulo-interno">
            <h1><i class="fa fa-caret-right"></i>
                @foreach($product->categories as $key => $category)
                    {{$category->category->title}}
                @endforeach
            </h1>
        </div>

        <div class="menu-izq-paginador">
            <div class="menu-flotante-izq">
                <a href="#"data-toggle="modal" data-target="#modal_aside_right"> <i class="fa fa-bars"></i> Ver Menú Categorías</a>
            </div>
        </div>

        @if (Session::has('error'))
            @if (Session::get('error') == 'success')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Producto agregado al carro!</strong> Sigue comprando. <a href="{{ route('mi.carro') }}" class="alert-link">VER CARRITO</a>.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @endif

        <!-- producto detalle -->
        <div class="cont-contenedor">
            <div class="row">

                <div class="col-md-6 mb-2">
                    <!-- FOTO ZOOM DETALLE >> fotos aquí -->
                    <div class="flexslider11">
                        <ul class="slides">

                        @if (count($product->galleries)>0)

                            @foreach ($product->galleries as $key => $value)
                                <li data-thumb="{{ asset('/files/productsGalleries/img/' . $value->image) }}">
                                    <div class="thumb-image">
                                        <a href="{{ asset('/files/productsGalleries/img/' . $value->image) }}" data-fancybox="images">
                                            <img src="{{ asset('/files/productsGalleries/img/' . $value->image) }}" class="img-fluid" alt=".">
                                        </a>
                                    </div>
                                </li>
                            @endforeach

                            @else
                                <li data-thumb="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}">
                                    <div class="thumb-image">
                                        <a href="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" data-fancybox="images">
                                            <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid" alt=".">
                                        </a>
                                    </div>
                                </li>

                        @endif

                        </ul>
                    </div><!-- Imagen producto detalle -->
                </div>

                <div class="col-md-6">
                    <div class="product-dtl">

                        <div class="product-info">
                            <div class="product-name mb-2">
                                {{$product->title ? $product->title : 'SIN TITULO'}}
                            </div>
                            <input type="text" name="products_id" value="{{ $product->id }}" hidden />
                            <p>
                                @if($product->sku)
                                    <strong>COD:</strong> {{$product->sku}}
                                @endif
                            </p>
                            <p>
                                <strong>DISPONIBILIDAD:</strong> {{$product->stock}} Disponibles
                            </p>

                            @if($product->offer_price == '0')

                                @if($product->normal_price != 0)
                                    <!-- precio normal -->
                                    <div class="product-price-normal">
                                        <span>${{ convertirValor($product->normal_price) }}</span>
                                    </div>

                                    @else
                                    <div class="product-price-normal">
                                        <h6>PRECIO NO DISPONIBLE</h6>
                                    </div>

                                @endif

                                @else
                                <!-- oferta -->
                                <div class="product-price-discount">
                                    <p>-{{ obtenerPorcentajeDescuento($product->normal_price, $product->offer_price) }}%</p>
                                    <span>${{ convertirValor($product->offer_price) }}</span><span class="line-through">${{ convertirValor($product->normal_price) }}</span>
                                </div>
                            @endif

                        </div>

                        @php /*
                        <!-- color - producto -->
                        <div class="ws-productos-tipo-entrega">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="size">Color: <span>(204-3)</span></label>
                                    <form action="#" class="display-flex mb-4">
                                        <select id="inputState" class="form-control">
                                            <option selected class="color-rojo"> Rojo </option>
                                            <option class="color-negro">Negro</option>
                                            <option class="color-amarillo"> Amarillo</option>
                                            <option class="color-verde">Verde</option>
                                            <option class="color-cafe">Café</option>
                                            <option class="color-gris">Gris</option>
                                             <option class="color-morado">Morado</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- modelo - producto -->
                        <div class="ws-producto-caract-tallas2 card mt-3">
	        			    <h3>Modelo RL: <span>(203-4)</span> </h3>
                            <div class="filter-content collapse show" id="collapse_aside1">
                                <div class="card-body">
                                    <label class="checkbox-btn" disabled="disabled">
                                        <input type="checkbox">
                                        <span class="btn btn-light"> 11RL </span>
                                    </label>

                                    <label class="checkbox-btn">
                                        <input type="checkbox">
                                        <span class="btn btn-light"> 14RL </span>
                                    </label>

                                    <label class="checkbox-btn">
                                        <input type="checkbox">
                                        <span class="btn btn-light"> 3RL </span>
                                    </label>

                                    <label class="checkbox-btn">
                                        <input type="checkbox">
                                        <span class="btn btn-light"> 5RL </span>
                                    </label>

                                    <label class="checkbox-btn">
                                        <input type="checkbox">
                                        <span class="btn btn-light"> 7RL </span>
                                    </label>

                                    <label class="checkbox-btn">
                                        <input type="checkbox">
                                        <span class="btn btn-light"> 9RL </span>
                                    </label>
                                </div>
                            </div>
	        			</div>

                        <!-- tallas/medidas - producto -->
                        <div class="ws-producto-caract-tallas2 card mt-3">
	        			    <h3>Medidas: <span>(203-2)</span> </h3>
                            <div class="filter-content collapse show" id="collapse_aside1">
                                    <div class="card-body">
                                        <label class="checkbox-btn" disabled="disabled">
                                            <input type="checkbox">
                                            <span class="btn btn-light"> 10cm x 10mts</span>
                                        </label>

                                        <label class="checkbox-btn">
                                            <input type="checkbox">
                                            <span class="btn btn-light"> 15cm x 10mts</span>
                                        </label>

                                        <label class="checkbox-btn">
                                            <input type="checkbox">
                                            <span class="btn btn-light"> 20cm x 10mts </span>
                                        </label>
                                    </div>
                            </div>
	        			</div>
                        */ @endphp

                        <!-- agg carrito - producto -->
                        <div class="product-count">
                            <div class="row">
                                <div class="col-lg-12">

                                    @if ($product->stock > 0)

                                        @php
                                            $agregado = 0;
                                        @endphp
                                        @foreach (Cart::getContent() as $detail)

                                            @if ($detail->attributes['sku'] == $product->sku)
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
                                                <input type="text" name="products_id" value="{{ $product->id }}" hidden />
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

                        <!-- tipo entrega - producto -->
                        <div class="ws-productos-tipo-entrega">
                            <h2>TIPO ENTREGA</h2>

                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('images/icon-domicilio.svg')}}" alt="domicilio">
                                    <p>Despacho a Domicilio</p>
                                    @if($product->shipping_active == 1)
                                        <p><span>Disponible</span></p>
                                        @else
                                        <p><strong>No Disponible</strong></p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <img src="{{asset('images/icon-local.svg')}}" alt="retiro en tienda">
                                    <p>Retiro en Tienda</p>
                                    @if($product->office_shipping_active == 1)
                                        <p><span>Disponible</span></p>
                                        @else
                                        <p><strong>No Disponible</strong></p>
                                    @endif
                                  </div>
                            </div>
                        </div>

                        <!-- TABS PESTAÑA INFORMACIÓN PRODUCTO -->
                        <div>
                            <div class="product-info-tabs cont-contenedor">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">

                                    @php
                                        $nav_active1 = 0;
                                        $nav_active2 = 0;
                                        $nav_active3 = 0;

                                        if ($product->general_description) {
                                            $nav_active1 = 1;
                                        } else {
                                            if ($product->technical_description) {
                                                $nav_active2 = 1;
                                            } else {
                                                if ($product->archive) {
                                                    $nav_active3 = 1;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if ($product->general_description)
                                        <li class="nav-item">
                                        <a class="nav-link @if($nav_active1 == 1) active @endif" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Descripción</a>
                                        </li>
                                    @endif

                                    @if ($product->technical_description)
                                        <li class="nav-item">
                                            <a class="nav-link @if($nav_active2 == 1) active @endif" id="fichatecnica-tab" data-toggle="tab" href="#datostecnicos" role="tab" aria-controls="review" aria-selected="false">Datos Técnicos</a>
                                        </li>
                                    @endif

                                    @if ($product->archive)
                                        <li class="nav-item">
                                        <a class="nav-link @if($nav_active3 == 1) active @endif" id="fichatecnica-tab" data-toggle="tab" href="#fichatecnica" role="tab" aria-controls="review" aria-selected="false">Ficha Técnica</a>
                                        </li>
                                    @endif

                                </ul>

                                <div class="tab-content" id="myTabContent">

                                    @php
                                        $tab_active1 = 0;
                                        $tab_active2 = 0;
                                        $tab_active3 = 0;

                                        if ($product->general_description) {
                                            $tab_active1 = 1;
                                        } else {
                                            if ($product->technical_description) {
                                                $tab_active2 = 1;
                                            } else {
                                                if ($product->archive) {
                                                    $tab_active3 = 1;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if ($product->general_description)
                                        <div class="tab-pane fade show @if($tab_active1 == 1) active @endif" id="description" role="tabpanel" aria-labelledby="description-tab">
                                            <p>{!! $product->general_description !!}</p>
                                        </div>
                                    @endif

                                    @if ($product->technical_description)
                                        <div class="tab-pane fade show @if($tab_active2 == 1) active @endif" id="datostecnicos" role="tabpanel" aria-labelledby="datostecnicos-tab">
                                            <p>{!! $product->technical_description !!}</p>
                                        </div>
                                    @endif

                                    @if ($product->archive)
                                        <div class="tab-pane fade show @if($tab_active3 == 1) active @endif" id="fichatecnica" role="tabpanel" aria-labelledby="fichatecnica-tab">
                                            <div class="ficha-tecnica">
                                                <p><strong><i class="fa fa-download"></i> Ver/Descargar Ficha Técnica: </strong> </p>
                                                <p><a href="{{ asset('/files/products/pdf/' . $product->archive) }}"
                                                download="ficha_tecnica_{{ $product->slug }}">DESCARGAR</a></p>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="productos-relacionados mt-5">
            <h4>Otros Productos Relacionados</h4>
        </div>

        <div class="swiper-container swiper-relacionados">

            @if(count($productFeatured)<=4)

                <div class="row">
                    @foreach($productFeatured as $key => $value)
                        <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                            <div class="sv-producto-mod">
                                <a href="{{ route('detalle.producto', $value->slug_producto) }}">

                                    @php
                                        $existe = false;
                                    @endphp
                                    @foreach($images_product as $key => $value_image)
                                        @foreach($value_image as $key => $value_final)
                                            @if($value_final->products_id == $value->product_id && $value_final->position == 1)
                                                <img src="{{ asset('/files/productsGalleries/img/' . $value_final->image) }}" class="img-fluid hvr-shrink" alt=".">
                                                @php
                                                    $existe = true;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endforeach

                                    @if(!$existe)
                                        <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid hvr-shrink" alt=".">
                                    @endif

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
                                                    <input type="text" name="products_id" value="{{ $value->product_id }}" hidden />
                                                    <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1" hidden>
                                                    <input type="submit" class="btn btn-dark" value="AGREGAR AL CARRO">
                                                </form>
                                            @endif

                                            @else
                                            <a href="{{ route('detalle.producto', $value->slug_producto) }}" class="btn btn-dark">VER DETALLES</a>
                                        @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @else
                <div class="swiper-wrapper">
                    @foreach($productFeatured as $key => $value)
                        <div class="swiper-slide">
                            <div class="sv-producto-mod">
                                <a href="{{ route('detalle.producto', $value->slug_producto) }}">

                                    @php
                                        $existe = false;
                                    @endphp
                                    @foreach($images_product as $key => $value_image)
                                        @foreach($value_image as $key => $value_final)
                                            @if($value_final->products_id == $value->product_id && $value_final->position == 1)
                                                <img src="{{ asset('/files/productsGalleries/img/' . $value_final->image) }}" class="img-fluid hvr-shrink" alt=".">
                                                @php
                                                    $existe = true;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endforeach

                                    @if(!$existe)
                                        <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid hvr-shrink" alt=".">
                                    @endif

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
                                                <input type="text" name="products_id" value="{{ $value->product_id }}" hidden />
                                                <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1" hidden>
                                                <input type="submit" class="btn btn-dark" value="AGREGAR AL CARRO">
                                            </form>
                                        @endif

                                        @else
                                        <a href="{{ route('detalle.producto', $value->slug_producto) }}" class="btn btn-dark">VER DETALLES</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

            @endif

        </div>

        <!-- modal productos -->
        <x-menu-productos-modal></x-menu-productos-modal>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

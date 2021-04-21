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

        <div class="menu-izq-paginador">
            <div class="menu-flotante-izq d-block d-sm-none d-none d-sm-block d-md-none d-md-block d-lg-none">
                <a href="#"data-toggle="modal" data-target="#modal_aside_right"> <i class="fa fa-bars"></i> Ver Menú Categorías </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 d-none d-xl-block d-none d-lg-block d-xl-none">
                <!-- CATEGORÍAS MENÚ LATERAL VERSION DESKTOP -->
                <x-menu-productos></x-menu-productos>
            </div>

            <div class="col-md-12 col-lg-9">

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

                <div class="titulo-interiores-productos">
                    <h1><?=$PaginaTitulo?></h1>
                </div>

                <div class="vista-orden-productos">
                    <div>
                        <p>Productos <strong>({{$productsCategoriaN}})</strong></p>
                    </div>
                    <div class="p-list">
                        <div class="form-group">
                                <select class="form-control" id="exampleFormControlSelect1">
                                  <option>Ordenar por</option>
                                  <option>Precio de menor a mayor</option>
                                  <option>Precio de mayor a menor</option>
                                  <option>Nuevo Productos</option>
                                  <option>Más visitados</option>
                                </select>
                        </div>
                    </div>
                </div>

                <div class="row productos-interiores">

                    @if(count($productsCategoria) > 0)
                        @foreach($productsCategoria as $key => $value)

                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="sv-producto-mod">
                                    <a href="{{ route('detalle.producto', $value->product->slug) }}">
                                        @if (count($value->product->galleries) >0)
                                            <img src="{{ asset('/files/productsGalleries/img/' . $value->product->galleries[0]->image) }}" class="img-fluid " alt="."></a>
                                        @else
                                            <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid " alt="."></a>
                                        @endif
                                    </a>
                                    @foreach($value->product->categories as $key => $category)
                                        @if($category->category->level == 1)
                                            <p>{{$category->category->title}}</p>
                                        @endif
                                    @endforeach
                                    <h2 class="text-center"> {{$value->product->title}} </h2>

                                    <div class="precio-boton d-flex justify-content-center">
                                        @if($value->product->offer_price == '0')

                                            @if($value->product->normal_price != 0)
                                                <!-- precio normal -->
                                                <div class="sv-precio-normal text-center">
                                                    <h3>${{ convertirValor($value->product->normal_price) }}</h2>
                                                </div>
                                            @endif

                                            @else
                                                <!-- precio oferta -->
                                                <div class="sv-precio-oferta text-center">
                                                    <h4>${{ convertirValor($value->product->offer_price) }} <span>/ ${{ convertirValor($value->product->normal_price) }}</span></h4>
                                                    <div class="sv-oferta-ticket">
                                                        <p><span>-{{ obtenerPorcentajeDescuento($value->product->normal_price, $value->product->offer_price) }}%</span></p>
                                                    </div>
                                                </div>
                                        @endif
                                    </div>

                                    <div class="sv-producto-agregar sv-p-centrar">
                                        @if ($value->product->stock > 0)
                                            @php
                                                $agregado = 0;
                                            @endphp
                                            @foreach (Cart::getContent() as $detail)

                                                @if ($detail->attributes['sku'] == $value->product->sku)
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
                                                    <input type="text" name="products_id" value="{{ $value->product->id }}" hidden />
                                                    <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1" hidden>
                                                    <input type="submit" class="btn btn-dark" value="AGREGAR AL CARRO">
                                                </form>
                                            @endif

                                                @else
                                                <a href="{{ route('detalle.producto', $value->product->slug) }}" class="btn btn-dark">VER DETALLES</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @endforeach

                        @else
                        <div class="col-12">
                            <div class="resultado-productos">
                                <h6>No se encontraron productos que concuerden con la selección.</h6>
                            </div>
                        </div>

                    @endif

                </div>

                <div class="vista-paginas-productos mt-3">
                    <div>
                        <p>Productos <strong>({{$productsCategoriaN}})</strong></p>
                    </div>
                    <div class="p-paginas">
                        <nav aria-label="Page navigation example">
                            {{ $productsCategoria->links('pagination') }}
                        </nav>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

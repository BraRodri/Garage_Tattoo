<div class="swiper-wrapper">

    @if(count($ofertas)>0)

        @foreach($ofertas as $key => $value)

            <div class="swiper-slide">
                <div class="sv-producto-mod">
                    <a href="{{ route('detalle.producto', $value) }}">
                        @if (count($value->galleries) >0)
                            <img src="{{ asset('/files/productsGalleries/img/' . $value->galleries[0]->image) }}" class="img-fluid " alt="."></a>
                        @else
                            <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid " alt="."></a>
                        @endif
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

        @endforeach

    @endif

</div>

<!-- Add Arrows -->
<div class="swiper-button-next"></div>
<div class="swiper-button-prev"></div>

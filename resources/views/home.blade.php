<x-app-layout>

    @section('pagina')
        Inicio
    @endsection

    <x-aceptar-cookies></x-aceptar-cookies>

    <div class="container">

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

        <div class="row mb-5">
            <div class="col-lg-3">
                <!-- MENÚ PRINCIPAL -->
                <div class="menu-principal mb-4">
                    <h1>CATEGORÍAS</h1>
                    <!-- menú código aquí -->
                    <x-menu-principal></x-menu-principal>
                </div>
            </div>
            <div class="col-lg-9">
                <!-- SLIDER -->
                <div class="slider-principal">
                    <x-home-slider></x-home-slider>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8">
                <x-slider-promociones></x-slider-promociones>
            </div>
            <div class="col-lg-4">
                <div class="tipos-de-despachos">
                    <a href="{{route('seguimiento.pedido')}}"><img src="{{asset('images/btn-seguimiento-de-mi-pedido.jpg')}}" class="img-fluid hvr-grow" alt="seguimiento pedido"></a>
                    <img src="{{asset('dinamicos/contenidos/despachos-01.jpg')}}" class="img-fluid" alt=".">
                    <img src="{{asset('dinamicos/contenidos/despachos-02.jpg')}}" class="img-fluid" alt=".">
                    <img src="{{asset('dinamicos/contenidos/despachos-03.jpg')}}" class="img-fluid" alt=".">
                    <img src="{{asset('dinamicos/contenidos/despachos-04.jpg')}}" class="img-fluid" alt=".">
                </div>
            </div>
        </div>

        <!-- PRODUCTOS DESTACADOS -->
        <div class="p-titulo">
            <h1 class="text-center">NUESTROS DESTACADOS</h1>
        </div>
        <x-productos-destacados></x-productos-destacados>

        <!-- NUESTRAS OFERTAS -->
        <div class="p-titulo2">
            <h2 class="text-center">NUESTRAS <span>OFERTAS</span></h2>
        </div>

        <div class="swiper-container swiper-nuestrasofertas">
            <x-productos-ofertas></x-productos-ofertas>
        </div>

        <!-- BLOG Y SUSCRIBIRSE -->
        <div class="row mt-5 mb-5">
            <div class="col-lg-6">
                <x-blog-inicio></x-blog-inicio>
            </div>
            <div class="col-lg-6">
                <div class="portada-suscribirse">
                    <h2>NEWSLETTER</h2>
                       <p>Entérate primero de nuestras ofertas y novedades que tenemos para ti</p>
                        <div class="p-suscribir">
                            <div>
                                <input type="text" class="form-control" id="text" placeholder="Ingresa su E-mail">
                            </div>
                            <div>
                            <a href="#" class="btn btn-warning">SUSCRIBIRSE</a>
                            </div>
                        </div>
                </div>
            </div>

        </div>

        <!-- HORARIO, DESPACHO Y UBICACIÓN -->
        <div class="row">
            <div class="col-lg-4">
                <div class="portada-info text-white">
                    <h2><img src="{{ asset('images/icono-reloj.svg') }}" width="43" height="43" alt=""> HORARIO DE ATENCIÓN</h2>
                    {!! $info->horary !!}
                </div>
            </div>
            <div class="col-lg-4">
                <div class="portada-info text-white">
                    <h2><img src="{{ asset('images/icono-despacho-todo-chile.svg') }}" width="43" height="43" alt=""> DESPACHO A DOMICILIO</h2>
                    <img src="{{ asset('dinamicos/contenidos/logo-chilexpress.png') }}" class="img-fluid" alt="chilexpress">
                    <img src="{{ asset('dinamicos/contenidos/logo-starken.jpg') }}" class="img-fluid" alt="starken">
                    <p>Despacho a Regiones</p>
                    <hr>
                    <div class="portada-info-despacho">
                        <img src="{{ asset('images/icon-despacho-propio.svg') }}" width="60" class="img-fluid" alt="despacho propio">
                        <p>DESPACHO PROPIO PARA TODA LA REGIÓN METROPOLITANA</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="portada-info text-white">
                    <h2><img src="images/icono-ubicacion.svg" width="43" height="43" alt="">NUESTRA UBICACIÓN</h2>
                    <p><strong>{{$info->city}}</strong><br>
                    <a href="{{$info->map_1_link}}" target="_blank">{{$info->address}}</a></p>
                    <br>
                    <p><strong>{{$info->city_2}}</strong><br>
                    <a href="{{$info->map_2_link}}" target="_blank">{{$info->address_2}}</a></p>
                </div>
            </div>
        </div>

        <!-- LOGOTIPOS MARCAS -->
        <div class="mt-5 mb-4">
            <x-slider-marca></x-slider-marca>
        </div>

    </div>

    <x-modal-avisos></x-modal-avisos>

    <x-slot name="js">

        <!-- MODAL AUTOMÁTICO   -->
        <script>
            $(document).ready(function(){
                $("#myModal").modal('show');
            });
        </script>

    </x-slot>
</x-app-layout>

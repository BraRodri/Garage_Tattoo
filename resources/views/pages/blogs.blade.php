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
                <x-menu-izq-blog></x-menu-izq-blog>
            </div>
            <div class="col-md-9 cont-contenedor">
                <div class="row">

                    @if(count($blogs)>0)

                        @foreach($blogs as $key => $value)
                            <div class="col-md-12">
                                <div class="noticias-item noticia-mini">
                                    <div class="row noticias-horizontal">
                                        <div class="col-md-4">
                                            @if($value->image_main)
                                                <img src="{{ asset($value->image_main) }}" class="img-fluid hvr-shrink" alt=".">

                                                @else
                                                <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="hvr-shrink img-fluid" style="width: 90%" alt=".">
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <h2>{{$value->title}}.</h2>
                                            <p><i class="fa fa-calendar"></i> <span>{{$value->date_public}}</span></p>
                                            @php
                                                $dato = strip_tags($value->description);
                                            @endphp
                                            <p>{{ substr($dato, 0, 200) }}...</p>
                                            <a href="{{ route('blog.detalle', $value->slug)}}">
                                                <button type="button" class="btn btn-warning">Seguir Leyendo</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @else
                        <h5 class="p-4">Actualmente no hay noticias, vuelve pronto!.</h5>

                    @endif

                </div>
           </div>
        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

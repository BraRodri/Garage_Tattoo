<x-app-layout>

    @section('pagina')
        Blog / Noticias
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1>Blog / Noticias</h1>
        </div>

        <!-- página estandard EMPRESA -->
        <div class="row">
            <div class="col-md-3">
                <x-menu-izq-blog></x-menu-izq-blog>
            </div>
            <div class="col-md-9 cont-contenedor">
                <div class="noticias-item noticias-detalle">
                    <a class="btn btn-outline-secondary btn-volver" href="#" role="button" onclick="history.back()">
                        << Volver
                    </a>
                    <div class="clearfix"></div>
                    @if($blog->image_main)
                        <img src="{{ asset($blog->image_main) }}" class="img-fluid" alt=".">
                        @else
                        <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="img-fluid" alt=".">
                    @endif
                    <h2>{{$blog->title}}</h2>
                    <p><i class="fa fa-calendar"></i> <span>{{$blog->date_public}}</span></p>
                    <p>{!! $blog->description !!}</p>
                </div>
            </div>
        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

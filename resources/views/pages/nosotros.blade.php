<x-app-layout>

    @section('pagina')
        <?=$PaginaTitulo?>
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1><?=$PaginaTitulo?></h1>
        </div>

        <div class="row">

            <x-menu-informacion></x-menu-informacion>

            <div class="col-md-9 cont-contenedor">

                <img src="{{asset('dinamicos/contenidos/nosotros.jpg')}}" class="img-fluid mb-2" alt="nosotros">

                {!! $info->description !!}

            </div>

        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

<!-- IMAGEN ALERTA PROMOCIONES -->
@if(count($alerta_promociones)>0)

    @foreach($alerta_promociones as $key => $value)
        @if($value->location == "DESKTOP")
            <!-- VERSIÓN DESKTOP SE USA UNA MIAGEN DE 1920PX DE ANCHO-->
            <div class="alerta-header d-none d-sm-block">
                <div class="alert alert-light alert-dismissible fade show" role="alert">
                    <img src="{{asset($value->image)}}" class="img-fluid" alt=".">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            </div>

            @elseif($value->location == "MOVIL")
            <!-- VERSIÓN MÓVIL SE USA UNA IMAGEN DE 580PX DE ANCHO-->
            <div class="alerta-header d-block d-sm-none">
                <div class="alert alert-light alert-dismissible fade show" role="alert">
                    <img src="{{asset($value->image)}}" class="img-fluid" alt=".">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            </div>
        @endif
    @endforeach

@endif
<!-- FIN IMAGEN ALERTA PROMOCIONES -->

<header id="header">

    <article class="container">

         <div class="row">
             <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                 <div class="sv-logo">
                     <a href="{{ route('home') }}"><img src="{{asset('images/logotipo-garage-tattoo.png')}}" class="img-fluid" alt="garage tattoo"></a>
                 </div>
             </div>

             <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9 h-derecha">
                 <div class="h-datoscontacto">
                     <p>
                         <img src="{{asset('images/icon-fono.svg')}}" width="23" height="23" alt="fono">
                         Sucursal Providencia<a href="tel:{{$info->phone1}}">{{$info->phone1}}</a>
                     </p>
                     <p>
                         <img src="{{asset('images/icon-fono.svg')}}" width="23" height="23" alt="fono">
                         Venta Online <a href="tel:{{$info->phone2}}">{{$info->phone2}}</a>
                         <span><a href="{{ route('contacto') }}">Contáctenos</a></span>
                     </p>
                 </div>
                 <div class="h-buscador-carro">
                     <div class="h-buscador">
                         <!-- buscador -->
                         <div class="s131">
                             <form role="form" method="GET" action="{{ route('buscadorProductos') }}">
                                @csrf
                                 <div class="inner-form">
                                 <div class="input-field first-wrap">
                                     <input id="dato" name="dato" type="text" placeholder="Buscar..." required=""/>
                                 </div>
                                 <div class="input-field second-wrap">
                                     <div class="input-select">

                                     <select data-trigger="" name="categoria" id="categoria">

                                        <option value="">Seleccione</option>

                                        @if(count($menus)>0)

                                            @foreach($menus as $key => $value)
                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                            @endforeach

                                        @endif

                                     </select>

                                     </div>
                                 </div>
                                 <div class="input-field third-wrap">
                                     <button class="btn-search" type="submit"><i class="fa fa-search"></i></button>
                                 </div>
                                 </div>
                             </form>
                         </div>

                     </div>
                     <div class="h-carro">
                         <a href="{{ route('mi.carro') }}"><img src="{{asset('images/icon-canasta.svg')}}" width="35" height="35"  alt="carro"> <span>{{  Cart::getTotalQuantity() }}</span></a>
                     </div>
                     <div class="h-sesion">
                        @guest('client')
                            <a href="{{ route('login.client') }}"><img src="{{asset('images/icon-sesion.svg')}}" width="30" height="30" alt="iniciar sesion">INGRESO</a>
                        @else
                            <a href="{{ route('mi.cuenta') }}"><img src="{{asset('images/icon-sesion.svg')}}" width="30" height="30" alt="iniciar sesion">MI CUENTA</a>
                        @endguest
                     </div>
                 </div>

             </div>

         </div>

    </article>

</header>

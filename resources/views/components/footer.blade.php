<footer id="footer">

    <div class="container f-footer">

        <div class="row">
            <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                <div class="f-logo">
                    <a href="{{ route('home') }}"><img src="{{asset('images/logotipo-garage-tattoo.png')}}" alt="garage tattoo" class="img-fluid"></a>
                    <ul class="f-info">
                        <li><img src="{{asset('images/icono-whatsapp.svg')}}" width="20" height="20" alt="whatsappp"> <a href="https://api.whatsapp.com/send?phone={{$info->phone3}}" target="_blank">{{$info->phone3}}</a></li>
                        <li><i class="fa fa-phone"></i> <a href="tel:{{$info->phone3}}">{{$info->phone1}}</a></li>
                        <li><i class="fa fa-envelope-o"></i> <a href="mailto:{{$info->email}}">{{$info->email}}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                <h2>INFORMACIONES</h2>
                <ul>
                   <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                   <li><a href="{{ route('pago.seguro') }}">Pago Seguro</a></li>
                   <li><a href="{{ route('politica.envios') }}">Política de Envíos</a></li>
                   <li><a href="{{ route('devoluciones') }}">Devoluciones</a></li>
                   <li><a href="{{ route('terminosCondiciones') }}">Términos y Condiciones</a></li>
                   <li><a href="{{ route('all.blogs') }}">Blog</a></li>
                   <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                <h2>CLIENTES</h2>
                <ul>
                   <li><a href="{{ route('seguimiento.pedido') }}">Seguimiento de Pedidos</a></li>
                   <li><a href="{{ route('mi.carro') }}">Mi Carro</a></li>
                   <li><a href="{{ route('mi.cuenta') }}">Mi Cuenta</a></li>
                   <li><a href="{{ route('registro') }}">Registro</a></li>
                   <li><a href="{{ route('resetPassword') }}">Recuperar Clave</a></li>
                </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                <h2>REDES SOCIALES</h2>
                <div class="f-redes">
                    <a href="{{$info->social_facebook}}" target="_blank"><img src="{{asset('images/icono-facebook.svg')}}" alt="facebook" width="40" height="40"></a>
                    <a href="{{$info->social_instagram}}" target="_blank"><img src="{{asset('images/icono-instagram.svg')}}" alt="instagram" width="40" height="40"></a>
                </div>

                <h2 class="mt-3">PAGO SEGURO</h2>
                <img src="{{asset('images/formas-de-pago.png')}}" class="img-fluid" alt="formas de pago">
            </div>

        </div>

        <div class="visual">
            <a href="https://www.visualchile.cl" target="_blank">DISEÑO WEB VISUAL CHILE</a>
        </div>
    </div>
</footer>

<a href="#0" class="cd-top">Top</a><!-- BackTop -->





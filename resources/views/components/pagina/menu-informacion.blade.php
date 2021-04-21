<div class="col-md-3">

    <div class="list-group">
      <a href="{{ route('nosotros') }}" class="list-group-item list-group-item-action {{ ! Route::is('nosotros') ?: 'active' }}">Nosotros</a>
      <a href="{{ route('pago.seguro') }}" class="list-group-item list-group-item-action {{ ! Route::is('pago.seguro') ?: 'active' }}">Pago seguro</a>
      <a href="{{ route('politica.envios') }}" class="list-group-item list-group-item-action {{ ! Route::is('politica.envios') ?: 'active' }}">Política de Envíos</a>
      <a href="{{ route('devoluciones') }}" class="list-group-item list-group-item-action {{ ! Route::is('devoluciones') ?: 'active' }}">Devoluciones</a>
      <a href="{{ route('terminosCondiciones') }}" class="list-group-item list-group-item-action {{ ! Route::is('terminosCondiciones') ?: 'active' }}">Términos y Condiciones</a>
      <a href="{{ route('all.blogs') }}" class="list-group-item list-group-item-action {{ ! Route::is('all.blogs') ?: 'active' }}">Blog</a>
      <a href="{{ route('contacto') }}" class="list-group-item list-group-item-action {{ ! Route::is('contacto') ?: 'active' }}">Contacto</a>
    </div>

</div>

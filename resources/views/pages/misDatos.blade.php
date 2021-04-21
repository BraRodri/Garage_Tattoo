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

                <div class="list-group">
                    <a href="{{ route('mi.cuenta') }}" class="list-group-item list-group-item-action {{ ! Route::is('mi.cuenta') ?: 'active' }}">Mi Cuenta</a>
                    <a href="{{ route('mis.pedidos') }}" class="list-group-item list-group-item-action {{ ! Route::is('mis.pedidos') ?: 'active' }}">Estado Pedidos</a>
                    <a href="{{ route('mis.datos') }}" class="list-group-item list-group-item-action {{ ! Route::is('mis.datos') ?: 'active' }}">Detalles de la Cuenta</a>
                    <a href="{{ route('mis.puntos') }}" class="list-group-item list-group-item-action {{ ! Route::is('mis.puntos') ?: 'active' }}">Mis Puntos</a>
                    <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">Salir</a>
                </div>

            </div>

            <div class="col-md-9 cont-contenedor">
                <div class="card">
                    <div class="card-header text-center">Mis Datos como Cliente</div>
                    <div class="card-body">
                        <p class="text-center mb-5">Tus datos de Registro, puedes modificar los campos habilitados.</p>
                        <form class="registro-clientes">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Correo</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="nombre@correo.com" value="{{$datos->email}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Rut</label>
                                        <input type="email" class="form-control" id="rut" name="rut" placeholder="ej: 11111111-1" value="{{$datos->rut}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Nombre y Apellidos</label>
                                        <input type="email" class="form-control" id="business_name" name="business_name" placeholder="ej: Pedro Salas  Medina" value="{{$datos->business_name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Teléfono o Celular</label>
                                        <input type="email" class="form-control" id="phone" name="phone" placeholder="ej:+5622 1234 5678" value="{{$datos->phone}}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>

                                <div class="mx-auto">
                                <a href="mis-datos-cuenta.php" class="btn btn-warning">Guardar Cambios</a>
                                </div>

                        <div class="col-lg-12 mt-4">
                          <hr class="mb-4">
                          <a href="#" class="btn btn-warning mb-4" data-toggle="modal" data-target="#agregar-direccion">AGREGAR DIRECCIÓN</a>
                          <h2>MIS DIRECCIONES</h2>
                          <div class="agregar-direcciones">
                            <div class="mis-direcciones">
                              <div class="row">
                                <div class="col-md-8">
                                  <p><strong>Alias:</strong> Casa 2</p>
                                  <p><strong>Dirección:</strong> Mario lincon N°124</p>
                                  <p><strong>Región:</strong> Región Metropolitana <strong>Ciudad:</strong> Santiago <strong>Comuna:</strong> San Bernardo.</p>
                                  <p><strong>Fono:</strong> 912345678</p>
                                  <div></div>
                                </div>
                                <div class="col-md-4">
                                  <button type="button" class="btn btn-warning"><i class="fa fa-pencil"></i> Editar</button>
                                  <button type="button" class="btn btn-dark" id="8290"><i class="fa fa-times"></i> Eliminar</button>
                                </div>
                              </div>
                            </div>
                            <div class="mis-direcciones">
                              <div class="row">
                                <div class="col-md-8">
                                  <p><strong>Alias:</strong> Trabajo</p>
                                  <p><strong>Dirección:</strong> Apoquindo 4400</p>
                                  <p><strong>Región:</strong> Región Metropolitana <strong>Ciudad:</strong> Santiago <strong>Comuna:</strong>Las Condes.</p>
                                  <p><strong>Fono:</strong> 912345678</p>
                                  <div></div>
                                </div>
                                <div class="col-md-4">
                                  <button type="button" class="btn btn-warning"><i class="fa fa-pencil"></i> Editar</button>
                                  <button type="button" class="btn btn-dark" id="8290"><i class="fa fa-times"></i> Eliminar</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
            </div>

        </div>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>

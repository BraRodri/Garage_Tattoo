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
                            <div class="row">

                                <div class="col-lg-12">
                                    @if (Session::has('error'))

                                        <!-- datos cliente -->
                                        @if (Session::get('error') == 'success_datos')
                                            <div class="alert alert-success">
                                                <strong class="text-dark">OK!</strong> Tus datos fueron actualizados correctamente.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                        @if (Session::get('error') == 'failure_datos')
                                            <div class="alert alert-danger">
                                                <strong class="text-dark">ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
                                                Si el error persiste favor comunicarse con nosotros.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif

                                        <!-- direcciones -->
                                        @if (Session::get('error') == 'success_address')
                                            <div class="alert alert-success">
                                                <strong class="text-dark">OK!</strong> Tu nueva dirección fue agregada correctamente.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                        @if (Session::get('error') == 'success_address_delete')
                                            <div class="alert alert-success">
                                                <strong class="text-dark">OK!</strong> La dirección fue eliminada correctamente.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                        @if (Session::get('error') == 'success_address_update')
                                            <div class="alert alert-success">
                                                <strong class="text-dark">OK!</strong> La dirección fue actualizada correctamente.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="col-lg-12">
                                    <form role="form" class="needs-validation" action="{{route('mis.datos.update')}}" method="POST" novalidate>
                                        <div class="row">
                                            @csrf
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="">Correo</label>
                                                    <input type="email" class="form-control required email" name="email" id="email" maxlength="100" placeholder="nombre@correo.com" value="{{$datos->email}}" required="" />
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Nombre y Apellidos</label>
                                                    <input type="text" class="form-control required" name="business_name" id="business_name" maxlength="255" placeholder="ej: Pedro Salas Medina" value="{{$datos->business_name}}" required="" />
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Rut</label>
                                                    <input type="text" class="form-control required rut" name="rut" id="rut" placeholder="11.111.111-1" maxlength="12" value="{{$datos->rut}}" required="" />
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Teléfono o Celular</label>
                                                    <input type="text" class="form-control required" name="phone" id="phone" maxlength="50" placeholder="ej: +56 22 1234 5678" value="{{$datos->phone}}" required="" />
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="">Dirección</label>
                                                    <input type="text" class="form-control" name="document_address" id="document_address" maxlength="255" placeholder="Nombre Calle y número" value="{{$datos->address}}"  required="" />
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Región</label>
                                                    <select class="form-control" name="document_regions_id" id="document_regions_id" required="">
                                                            <option value="">Seleccionar</option>

                                                            <?php
                                                                foreach ($regions as $region) {
                                                                    $selected = isset($datos['regions_id']) &&
                                                                    !empty($datos['regions_id']) &&
                                                                    $datos['regions_id'] == $region['id'] ?
                                                                    'selected="selected"' : ''; ?>

                                                                    <option value="<?php echo $region['code']; ?>" <?php echo $selected;?> >
                                                                        <?php echo $region['code_internal'] . ' - Región de ' . $region['description']; ?>
                                                                    </option>
                                                            <?php } ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Provincia</label>
                                                    <select class="form-control" name="document_provinces_id" id="document_provinces_id" required="">
                                                        <option value="">Seleccionar</option>

                                                        <?php
                                                            foreach ($provinces as $province) {
                                                                $selected = isset($datos['provinces_id']) &&
                                                                !empty($datos['provinces_id']) &&
                                                                $datos['provinces_id'] == $province['id'] ?
                                                                'selected="selected"' : ''; ?>

                                                                <option value="<?php echo $province['code']; ?>" <?php echo $selected;?> >
                                                                    <?php echo $province['description']; ?>
                                                                </option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Comuna</label>
                                                    <select class="form-control" name="document_locations_id" id="document_locations_id" required="">
                                                        <option value="">Seleccionar</option>

                                                        <?php
                                                            foreach ($locations as $location) {
                                                                $selected = isset($datos['locations_id']) &&
                                                                !empty($datos['locations_id']) &&
                                                                $datos['locations_id'] == $location['id'] ?
                                                                'selected="selected"' : ''; ?>

                                                                <option value="<?php echo $location['code']; ?>" <?php echo $selected;?> >
                                                                    <?php echo $location['description']; ?>
                                                                </option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Por favor Completa este campo.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="">Contraseña</label>
                                                    <input type="password" class="form-control" name="password" id="password" maxlength="255" />
                                                </div>
                                            </div>

                                            <input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />

                                            <button type="submit" class="btn btn-warning mx-auto">Guardar Cambios</button>

                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-12 mt-4">
                                    <hr class="mb-4">
                                    <a href="#" class="btn btn-warning mb-4" data-toggle="modal" data-target="#agregar-direccion">AGREGAR DIRECCIÓN</a>

                                    <h2>MIS DIRECCIONES</h2>
                                    <div class="agregar-direcciones">

                                        @if(count($address)>0)

                                            @foreach($address as $key => $value_addres)
                                                <div class="mis-direcciones">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <p><strong>Alias:</strong> {{$value_addres->alias}}</p>
                                                            <p><strong>Dirección:</strong> {{$value_addres->address}}</p>
                                                            <p>
                                                                <strong>Región:</strong>
                                                                {{$value_addres->region->description}}
                                                                <strong>Provincia:</strong>
                                                                {{$value_addres->province->description}}
                                                                <strong>Comuna:</strong>
                                                                {{$value_addres->location->description}}.
                                                            </p>
                                                            <p><strong>Fono:</strong> {{$value_addres->address_number}}</p>
                                                            <div></div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <a href="#" class="btn btn-warning m-2" data-toggle="modal" data-target="#editar-direccion-{{$value_addres->id}}"><i class="fa fa-pencil"></i> Editar</a>
                                                            <a href="{{ route('mis.datos.delete', $value_addres->id) }}" alt="" class="btn btn-dark m-2"><i class="fa fa-times"></i> Eliminar</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="editar-direccion-{{$value_addres->id}}" tabindex="-1" role="dialog" aria-labelledby="vista-previa-producto" aria-hidden="true">

                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Datos de la dirección - "{{$value_addres->alias}}"</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body cont-contenedor">
                                                                <form role="form" class="direccion-validation" action="{{route('datos.address.update')}}" method="POST" novalidate>
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label for="">Alias</label>
                                                                                <input type="text" class="form-control" id="alias" name="alias" placeholder="Ejemplo: Casa Mamá" value="{{$value_addres->alias}}" required>
                                                                                <div class="invalid-feedback">
                                                                                    Por favor Completa este campo.
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="">Dirección</label>
                                                                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="nombre calle y número." value="{{$value_addres->address}}" required>
                                                                                <div class="invalid-feedback">
                                                                                    Por favor Completa este campo.
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="">Telefono/Celular</label>
                                                                                <input type="text" class="form-control" id="phone" name="phone" placeholder="ej: +56 22 1234 5678" value="{{$value_addres->address_number}}" required>
                                                                                <div class="invalid-feedback">
                                                                                    Por favor Completa este campo.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label for="">Región</label>
                                                                                <select class="form-control" id="region_new" name="region_new" required>
                                                                                    <option value="">- Seleccionar -</option>
                                                                                    <?php
                                                                                        foreach ($regions as $region) {
                                                                                            $selected = isset($value_addres['regions_id']) &&
                                                                                            !empty($value_addres['regions_id']) &&
                                                                                            $value_addres['regions_id'] == $region['id'] ?
                                                                                            'selected="selected"' : ''; ?>

                                                                                            <option value="<?php echo $region['code']; ?>" <?php echo $selected;?> >
                                                                                                <?php echo $region['code_internal'] . ' - Región de ' . $region['description']; ?>
                                                                                            </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <div class="invalid-feedback">
                                                                                    Por favor Completa este campo.
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="">Provincia</label>
                                                                                <select class="form-control" id="provincia_new" name="provincia_new" required>
                                                                                    <option value="">- Seleccionar -</option>
                                                                                    <?php
                                                                                        foreach ($provinces as $province) {
                                                                                            $selected = isset($value_addres['provinces_id']) &&
                                                                                            !empty($value_addres['provinces_id']) &&
                                                                                            $value_addres['provinces_id'] == $province['id'] ?
                                                                                            'selected="selected"' : ''; ?>

                                                                                            <option value="<?php echo $province['code']; ?>" <?php echo $selected;?> >
                                                                                                <?php echo $province['description']; ?>
                                                                                            </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <div class="invalid-feedback">
                                                                                    Por favor Completa este campo.
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="">Comuna</label>
                                                                                <select class="form-control" id="comuna_new" name="comuna_new" required>
                                                                                    <option value="">- Seleccionar -</option>
                                                                                    <?php
                                                                                        foreach ($locations as $location) {
                                                                                            $selected = isset($value_addres['locations_id']) &&
                                                                                            !empty($value_addres['locations_id']) &&
                                                                                            $value_addres['locations_id'] == $location['id'] ?
                                                                                            'selected="selected"' : ''; ?>

                                                                                            <option value="<?php echo $location['code']; ?>" <?php echo $selected;?> >
                                                                                                <?php echo $location['description']; ?>
                                                                                            </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <div class="invalid-feedback">
                                                                                    Por favor Completa este campo.
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <input type="hidden" name="idAddress" value="{{$value_addres->id}}" />
                                                                        <input type="hidden" name="idCliente" value="<?php echo $datos['id']; ?>" />
                                                                        <button type="submit" class="btn btn-warning mx-auto">Editar Dirección</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endforeach

                                            @else
                                            <div class="col-lg-12">
                                                <h6 class="text-white p-2">Actualmente no tienes direcciones agregadas, agrega una!.</h6>
                                            </div>

                                        @endif

                                    </div>
                                </div>

                            </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="agregar-direccion" tabindex="-1" role="dialog" aria-labelledby="vista-previa-producto" aria-hidden="true">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nueva Dirección</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body cont-contenedor">
                        <form role="form" class="direccion-validation" action="{{route('mis.datos.enter')}}" method="POST" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Alias</label>
                                        <input type="text" class="form-control" id="alias" name="alias" placeholder="Ejemplo: Casa Mamá" required>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="nombre calle y número." required>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Telefono/Celular</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="ej: +56 22 1234 5678" required>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Región</label>
                                        <select class="form-control" id="region_new" name="region_new" required>
                                            <option value="">- Seleccionar -</option>
                                            <?php if (count($regions) > 0) {
                                                foreach ($regions as $region) { ?>
                                                <option
                                                    value="<?php echo $region['code']; ?>">
                                                    <?php echo $region['code_internal'] . ' -
                                                    Región de ' . $region['description']; ?>
                                                </option>
                                                <?php }
                                            } ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Provincia</label>
                                        <select class="form-control" id="provincia_new" name="provincia_new" required>
                                            <option value="">- Seleccionar -</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Comuna</label>
                                        <select class="form-control" id="comuna_new" name="comuna_new" required>
                                            <option value="">- Seleccionar -</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="idCliente" value="<?php echo $datos['id']; ?>" />
                                <button type="submit" class="btn btn-warning mx-auto">Agregar Dirección</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <x-slot name="js">

        <script type="text/javascript">

            jQuery('select[name=regions_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=provinces_id]').html("");
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=provinces_id]').load('<?php echo BASE_URL_ROOT?>'+'provinces/' + code);
            });

            jQuery('select[name=provinces_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=locations_id]').load('<?php echo BASE_URL_ROOT?>'+'locations/' + code);
            });

            jQuery('select[name=document_regions_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=document_provinces_id]').html("");
                jQuery('select[name=document_locations_id]').html("");
                jQuery('select[name=document_provinces_id]').load('<?php echo BASE_URL_ROOT?>'+'provinces/' + code);
            });

            jQuery('select[name=document_provinces_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=document_locations_id]').html("");
                jQuery('select[name=document_locations_id]').load('<?php echo BASE_URL_ROOT?>'+'locations/' + code);
            });

            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
                });
            }, false);
            })();

            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('direccion-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
                });
            }, false);
            })();

            jQuery('select[name=regions_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=provinces_id]').html("");
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=provinces_id]').load('<?php echo BASE_URL_ROOT?>'+'provinces/' + code);
            });

            jQuery('select[name=provinces_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=locations_id]').load('<?php echo BASE_URL_ROOT?>'+'locations/' + code);
            });

            jQuery('select[name=region_new]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=provincia_new]').html("");
                jQuery('select[name=comuna_new]').html("");
                jQuery('select[name=provincia_new]').load('<?php echo BASE_URL_ROOT?>'+'provinces/' + code);
            });

            jQuery('select[name=provincia_new]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=comuna_new]').html("");
                jQuery('select[name=comuna_new]').load('<?php echo BASE_URL_ROOT?>'+'locations/' + code);
            });

        </script>

    </x-slot>
</x-app-layout>

<x-app-layout>

    @section('pagina')
        <?=$PaginaTitulo?>
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1><?=$PaginaTitulo?></h1>
        </div>


        @if (Session::has('error'))
                @if (Session::get('error') == 'failure')

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error al registrar!</strong>Si el error persiste comuniquese con nosotros
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                @endif
                @if (Session::get('error') == 'success')

                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Registro exitoso!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                @endif

        @endif

        <main class="login-form mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8 mt-2 mb-2 card-user">
                    <div class="card">
                        <div class="card-header text-center">Registro Cliente </div>
                        <div class="card-body">
                           <p class="text-center mb-5">Complete el siguiente Formulario para registarse en nuestro sitio web.</p>

                            <form role="form" class="registro-clientes needs-validation form-groups-bordered" id="form1" method="post"
                                action="{{route('clientes.agregar')}}"
                                enctype="multipart/form-data" novalidate>

                                @csrf
                              <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Correo</label>
                                        <input type="email" class="form-control required email" name="email" id="email" maxlength="100" placeholder="nombre@correo.com" required="" />
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Nombre y Apellidos</label>
                                        <input type="text" class="form-control required" name="business_name" id="business_name" maxlength="255" placeholder="ej: Pedro Salas Medina" required="" />
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Rut</label>
                                        <input type="text" class="form-control required rut" name="rut" id="rut" placeholder="11.111.111-1" maxlength="12" required="" />
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Teléfono o Celular</label>
                                        <input type="text" class="form-control required" name="phone" id="phone" maxlength="50" placeholder="ej: +56 22 1234 5678" required="" />
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Dirección</label>
                                        <input type="text" class="form-control" name="document_address" id="document_address" maxlength="255" placeholder="Nombre Calle y número" required="" />
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Región</label>
                                        <select class="form-control" name="document_regions_id" id="document_regions_id" required="">
                                                <option value="">Seleccionar</option>
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
                                        <label for="exampleFormControlSelect1">Provincia</label>
                                        <select class="form-control" name="document_provinces_id" id="document_provinces_id" required="">
                                            <option value="">Seleccionar</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Comuna</label>
                                        <select class="form-control" name="document_locations_id" id="document_locations_id" required="">
                                            <option value="">Seleccionar</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor Completa este campo.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Contraseña</label>
                                        <input type="password" class="form-control" name="password" id="password" maxlength="255" required="" />
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor Completa este campo.
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-warning mx-auto">REGISTRAR</button>
                              </div>
                            </form>
                    </div>
                </div>

            </div>

        </main>

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

    </script>

    <script>
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
    </script>

    </x-slot>
</x-app-layout>


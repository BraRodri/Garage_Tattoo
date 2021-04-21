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

                            <form role="form" class="registro-clientes" id="form1" method="post"
                                action="{{route('clientes.agregar')}}"
                                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                                @csrf
                              <div class="row">
                                <div class="col-lg-6">
                                      <div class="form-group">
                                        <label for="exampleFormControlInput1">Correo</label>
                                        <input type="text" class="form-control required email" name="email" id="email" maxlength="100" placeholder="nombre@correo.com" />
                                      </div>
                                      <div class="form-group">
                                        <label for="exampleFormControlInput1">Nombre y Apellidos</label>
                                        <input type="text" class="form-control required" name="business_name" id="business_name" maxlength="255" placeholder="ej: Pedro Salas Medina" />
                                      </div>
                                      <div class="form-group">
                                        <label for="exampleFormControlInput1">Rut</label>
                                        <input type="text" class="form-control required rut" name="rut" id="rut" placeholder="11.111.111-1" maxlength="12" />
                                      </div>
                                      <div class="form-group">
                                        <label for="exampleFormControlInput1">Teléfono o Celular</label>
                                        <input type="text" class="form-control required" name="phone" id="phone" maxlength="50" placeholder="ej: +56 22 1234 5678" />
                                      </div>

                                </div>
                                <div class="col-lg-6">
                                      <div class="form-group">
                                        <label for="exampleFormControlInput1">Dirección</label>
                                        <input type="text" class="form-control" name="document_address" id="document_address" maxlength="255" />
                                      </div>
                                      <div class="form-group">
                                        <label for="exampleFormControlSelect1">Región</label>
                                        <select class="form-control" name="document_regions_id" id="document_regions_id">
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
                                      </div>
                                      <div class="form-group">
                                        <label for="exampleFormControlSelect1">Provincia</label>
                                        <select class="form-control" name="document_provinces_id" id="document_provinces_id">
                                            <option value="">Seleccionar</option>
                                        </select>
                                      </div>
                                      <div class="form-group">
                                        <label for="exampleFormControlSelect1">Comuna</label>
                                        <select class="form-control" name="document_locations_id" id="document_locations_id">
                                            <option value="">Seleccionar</option>
                                        </select>
                                      </div>
                                </div>
                                    <div class="col-lg-12">
                                      <div class="form-group">
                                        <label for="exampleFormControlInput1">Contraseña</label>
                                        <input type="password" class="form-control" name="password" id="password" maxlength="255" />
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

    </x-slot>
</x-app-layout>


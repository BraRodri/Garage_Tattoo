<x-app-layoutt>

    @section('meta')
        <meta name="csrf-token" content="{{ csrf_token() }}">

    @endsection

    <ol class="breadcrumb bc-2">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <?php echo $parent_title; ?>
        </li>
        <li>
            <a href="<?php echo BASE_URL . $module; ?>"><?php echo
                $title; ?></a>
        </li>
        <li class="active">
            <strong>Nuevo Ingreso</strong>
        </li>
    </ol>

    <h3><?php echo $title; ?></h3>
    <br />

    <div class="clearfix"></div>

    @if (Session::has('error'))
    @if (Session::get('error') == 'upload')
        <div class="alert alert-danger"><strong>ERROR!</strong> El archivo no se pudo cargar. Asegúrese de que su
            archivo no supere el tamaño indicado o no cumpla con el formato establecido.</div>
    @endif
    @if (Session::get('error') == 'failure')
        <div class="alert alert-danger"><strong>ERROR! 1</strong> Se ha producido un error, favor vuelva a intentarlo.
            Si el error persiste favor comunicarse al administrador.</div>
    @endif
    @if (Session::get('error') == 'duplicate')
        <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, el slider que intenta
            ingresar ya se encuentra registrado.</div>
    @endif
@endif

    <form role="form" id="form1" method="post"
        action="{{route('clients.insert')}}"
        enctype="multipart/form-data" class="custom-validate form-groups-bordered">

        @csrf
        <div class="row">
            <div class="col-md-12">

                <ul class="nav nav-tabs left-aligned">
                    <!-- available classes "bordered", "right-aligned" -->
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            <span><i class="fa fa-user" aria-hidden="true"></i></span>
                            <span>Cliente</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <span><i class="fa fa-file-text" aria-hidden="true"></i></span>
                            <span>Datos de Facturación</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content tab-validate">
                    <div class="tab-pane active" id="tab1">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Tipo Cliente</label>
                                        <select class="form-control required" name="type" id="type">
                                            <option value="">Seleccionar</option>
                                            <option value="PERSONA">Persona</option>
                                            <option value="EMPRESA">Empresa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Rut</label>
                                    <input type="text" class="form-control required rut" name="rut" id="rut"
                                        placeholder="11.111.111-1" maxlength="12" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Nombre / Razón Social</label>
                                    <input type="text" class="form-control required" name="business_name"
                                        id="business_name" maxlength="255" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Giro</label>
                                    <input type="text" class="form-control" name="commercial_business"
                                        id="commercial_business" maxlength="255" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Teléfono</label>
                                    <input type="text" class="form-control required" name="phone" id="phone"
                                        maxlength="50" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control required email" name="email" id="email"
                                        maxlength="100" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Clave Automática?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="generate_password" id="generate_password"
                                                value="1" checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">
                                        * Al seleccionar generación de clave automática, no será necesario completar
                                        clave.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Clave</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        maxlength="255" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Activar?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="active" id="active" value="1"
                                                checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="tab2">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">Dirección</label>
                                    <input type="text" class="form-control" name="document_address"
                                        id="document_address" maxlength="255" />
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label">N°</label>
                                                <input type="text" class="form-control" name="document_address_number"
                                                    id="document_address_number" maxlength="100" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label">N° Depto / Oficina</label>
                                                <input type="text" class="form-control" name="document_office_number"
                                                    id="document_office_number" maxlength="100" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="form-group content-width-full">
                                                <div class="input-group content-width-full">
                                                    <label class="control-label">Región</label>
                                                    <select class="form-control" name="document_regions_id"
                                                        id="document_regions_id">
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
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="form-group content-width-full">
                                                <div class="input-group content-width-full">
                                                    <label class="control-label">Provincia</label>
                                                    <select class="form-control" name="document_provinces_id"
                                                        id="document_provinces_id">
                                                        <option value="">Seleccionar</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="form-group content-width-full">
                                                <div class="input-group content-width-full">
                                                    <label class="control-label">Comuna</label>
                                                    <select class="form-control" name="document_locations_id"
                                                        id="document_locations_id">
                                                        <option value="">Seleccionar</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="formNote">
                                        * Los datos adicionales de rut, razón social y giro serán tomados de los datos
                                        de cliente.
                                    </label>
                                </div>

                            </div>

                        </div>

                    </div>

                    <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>
                    <div class="form-group">
                        <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                        <a href="{{route('clients')}}"
                            type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                            Volver</a>
                    </div>

                </div>

            </div>
        </div>

    </form>

    <input type="text" value="{{csrf_token()}}" name="_token" hidden>

    <br />

    <x-slot name="js">

        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $.validator.addMethod("validateRolUnicoTributario", function(value, element) {
                    var label = element.id;

                    return this.optional(element) || validaRut(value, label);
                }, "El RUT ingresado es inválido");

                $.validator.addClassRules({
                    rut: {
                        validateRolUnicoTributario: true
                    }
                });

                $('#form1').validate({
                    errorElement: 'span',
                    errorClass: 'validate-has-error',
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('validate-has-error');
                        $(element).addClass('error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('validate-has-error');
                        $(element).removeClass('error');
                    },
                    errorPlacement: function(error, element) {
                        if (element.closest('.has-switch').length) {
                            error.insertAfter(element.closest('.has-switch'));
                        } else
                        if (element.parent('.checkbox, .radio').length || element.parent(
                                '.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    ignore: [],
                    invalidHandler: function() {
                        setTimeout(function() {
                            $('.nav-tabs a small.required').remove();
                            var validatePane = $(
                                '.tab-content.tab-validate .tab-pane:has(input.error), .tab-content.tab-validate .tab-pane:has(select.error)'
                                ).each(function() {
                                var id = $(this).attr('id');
                                $('.nav-tabs').find('a[href^="#' + id + '"]')
                                    .append(' <small class="required">***</small>');
                                console.log(id);

                                $('.nav-tabs li').removeClass('active');
                                $('.tab-content div').removeClass('active');

                                $('.nav-tabs').find('a[href^="#' + id + '"]')
                                    .parent().addClass('active');
                                $('.tab-content div#' + id + '').addClass('active');
                            });
                        });
                    },
                });

                $("#form1 select[name=type]").change(function() {
                    if ($(this).val() == 'EMPRESA') {
                        $('#commercial_business').rules('add', {
                            required: true
                        });
                    } else {
                        $('#commercial_business').rules('remove');
                    }
                });
            });

            jQuery('select[name=regions_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=provinces_id]').html("");
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=provinces_id]').load('<?php echo BASE_URL?>'+'clients/provinces/' + code);
            });

            jQuery('select[name=provinces_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=locations_id]').load('<?php echo BASE_URL?>'+'clients/locations/' + code);
            });

            jQuery('select[name=document_regions_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=document_provinces_id]').html("");
                jQuery('select[name=document_locations_id]').html("");
                jQuery('select[name=document_provinces_id]').load('<?php echo BASE_URL?>'+'clients/provinces/' + code);
            });

            jQuery('select[name=document_provinces_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=document_locations_id]').html("");
                jQuery('select[name=document_locations_id]').load('<?php echo BASE_URL?>'+'clients/locations/' + code);
            });

        </script>
    </x-slot>
</x-app-layoutt>

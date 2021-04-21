<x-app-layoutt>

    <ol class="breadcrumb bc-2">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <a href="<?php echo BASE_URL . $module; ?>"><?php echo
                $title; ?></a>
        </li>
        <li class="active">
            <strong>Editar Registro</strong>
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
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
                Si el error persiste favor comunicarse al administrador.</div>
        @endif
        @if (Session::get('error') == 'duplicate')
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, el slider que intenta
                ingresar ya se encuentra registrado.</div>
        @endif
    @endif


    <div class="panel panel-primary">

        <div class="panel-heading container-blue">
            <div class="panel-title">Formulario de Modificación</div>
        </div>

        <div class="panel-body color-gris-fondo">

            <form role="form" id="form1" method="post"
                action="{{route('locations.update')}}"
                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Región</label>
                        <select class="form-control required" name="region_code" id="region_code">
                            <option value="">Seleccionar</option>
                            <?php if (count($regions) > 0) {
                            foreach ($regions as $region) {
                            $selected = isset($location->province->region->id) &&
                            !empty($location->province->region->id) && $location->province->region->id ==
                            $region->id ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $region->code; ?>" <?php echo $selected; ?>><?php echo $region->code_internal
                            . ' - Región de ' . $region->description; ?></option>
                            <?php
                            }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Provincia</label>
                        <select class="form-control required" name="parent_code" id="parent_code">
                            <option value="">Seleccionar</option>
                            <?php if (count($provinces) > 0) {
                            foreach ($provinces as $province) {
                            $selected = isset($location->province->id) && !empty($location->province->id) &&
                            $location->province->id == $province->id ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $province->code; ?>" <?php echo $selected; ?>><?php echo
                            $province->description; ?></option>
                            <?php
                            }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Código</label>
                    <input type="text" class="form-control required" name="code" id="code" maxlength="50"
                        value="<?php echo $location->code; ?>" />
                </div>

                <div class="form-group">
                    <label class="control-label">Región</label>
                    <input type="text" class="form-control required" name="description" id="description" maxlength="100"
                        value="<?php echo $location->description; ?>" />
                </div>

                <div class="form-group">
                    <label class="control-label">Costo de Despacho</label>
                    <input type="text" class="form-control required digits" name="shipping_cost" id="shipping_cost"
                        value="<?php echo $location->shipping_cost; ?>" />
                </div>

                <div class="form-group">
                    <label class="control-label">¿Activar Despacho?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="active_shipping" id="active_shipping" value="1" <?php if ($location->active_shipping == 1) {
                            echo "checked='checked'";
                            } ?>>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="control-label">¿Activar Registro?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="active" id="active" value="1" <?php if
                                ($location->active == 1) {
                            echo "checked='checked'";
                            } ?>>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('locations')}}"
                        type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                    <input type="hidden" name="id"
                        value="<?php echo $location->id; ?>" />
                </div>

            </form>

        </div>

    </div>



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
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('validate-has-error');
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
                    }
                });
            });

            jQuery('select[name=region_code]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=parent_code]').html("");
                jQuery('select[name=parent_code]').load(_base_url_ + _url_friendly_base_ + module_name +
                    '/provinces/' + code);
            });

        </script>
    </x-slot>
</x-app-layoutt>

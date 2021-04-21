<x-app-layoutt>


    <ol class="breadcrumb bc-2">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <a href="<?php echo BASE_URL . $module; ?>"><?php echo $title;
                ?></a>
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
                action="<?php echo BASE_URL . URL_FRIENDLY_BASE . $module; ?>/update"
                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Ubicación</label>
                        <select class="form-control" name="location" id="location">
                            <option value="">Seleccionar</option>
                            <?php foreach ($options as $keyLocation => $valueLocation) { ?>
                            <?php $selection = $slider->location == $keyLocation ? "selected='selected'"
                            : ''; ?>
                            <option value="<?php echo $keyLocation; ?>" <?php echo $selection;?>><?php echo $valueLocation; ?></option>
                            <?php } ?>
                        </select>
                        @error('location')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Descripción</label>
                    <input type="text" class="form-control required" name="title" id="title" maxlength="255"
                        value="<?php echo $slider->title; ?>" />
                        @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="control-label">Imagen</label>
                    <div class="clearfix"></div>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <span class="btn btn-info btn-file">
                            <span class="fileinput-new">Seleccionar Imagen</span>
                            <span class="fileinput-exists">Cambiar</span>
                            <input type="file" name="image">
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                            style="float: none">&times;</a>
                            @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="clearfix"></div>
                    <label class="formNote">* Se recomienda subir imagen de <?php echo
                        $image_description; ?> px. En formato JPG.</label>
                    <div class="clearfix"></div>
                    <label class="formNote">* Tamaño máximo de carga <?php echo
                        Application\Helper::uploadSizeUser(); ?>.</label>
                </div>

                @if (!empty($modal->image))
                <div class="form-group">
                    <label class="control-label">Imagen Actual</label>
                    <div class="clearfix"></div>
                    <a href="{{asset($slider->image)}}"
                        data-fancybox="galeria"><img
                            src="{{asset($slider->image)}}"
                            width="170" height="101" /></a>
                </div>
                @endif

                <div class="form-group">
                    <label class="control-label">Enlace</label>
                    <input type="text" class="form-control url" name="link" id="link" maxlength="255"
                        value="<?php echo $slider->link; ?>" />
                    <label class="formNote">* Ej: https://www.google.cl/</label>
                    @error('link')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">¿Cómo se abre el enlace?</label>
                        <select class="form-control" name="target" id="target">
                            <option value="">Seleccionar</option>
                            <option value="_blank" <?php echo $slider->target == '_blank' ?
                                "selected='selected'" : ''; ?>>Nueva Pestaña</option>
                            <option value="_self" <?php echo $slider->target == '_self' ?
                                "selected='selected'" : ''; ?>>En la misma Pestaña</option>
                        </select>
                        @error('target')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">¿Activar Registro?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="active" id="active" value="1" <?php if
                                ($slider->active == 1) {
                            echo "checked='checked'";
                            } ?>>
                        </div>
                        @error('active')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('sliders')}}"
                        type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                    <input type="hidden" name="id" value="<?php echo $slider->id; ?>" />
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

                $('input[name=modificar_clave]').change(function() {
                    if ($(this).is(':checked')) {
                        $('input[name=clave]').addClass('required');
                    } else {
                        $('input[name=clave]').removeClass('required');
                    }
                });
            });

        </script>
    </x-slot>
</x-app-layoutt>

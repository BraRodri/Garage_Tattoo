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

            <div class="panel-options">
                <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
            </div>
        </div>

        <div class="panel-body color-gris-fondo">

            <form role="form" id="form1" method="post" action="{{ route('responses.update') }}"
                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                @csrf
                <div class="form-group">
                    <label class="control-label">Página</label>
                    <input type="text" class="form-control required" name="type" id="type" maxlength="255"
                        value="<?php echo $response->type; ?>" />
                    @error('type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="control-label">Título</label>
                    <input type="text" class="form-control required" name="title" id="title" maxlength="255"
                        value="<?php echo $response->title; ?>" />
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="control-label">Mensaje "OK"</label>
                            <textarea class="form-control ckeditor required" name="description1"
                                id="description1"><?php echo $response->description1; ?></textarea>
                        </div>
                    </div>
                    <?php
                    /*?>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="control-label">Shortcodes Editor</label>
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered">
                                    <tr>
                                        <td>Nombre de Usuario: <span class="label label-danger">{USER_NAME}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Email de Usuario: <span class="label label-danger">{USER_EMAIL}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Rut de Sociedad: <span class="label label-danger">{SOCIETY_RUT}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Nombre de Sociedad: <span class="label label-danger">{SOCIETY_NAME}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dirección de Sociedad: <span
                                                class="label label-danger">{SOCIETY_ADDRESS}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Cta Cte de Sociedad: <span
                                                class="label label-danger">{SOCIETY_CTA_CTE}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php */
                    ?>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="control-label">Mensaje "ERROR"</label>
                            <textarea class="form-control ckeditor required" name="description2"
                                id="description2"><?php echo $response->description2; ?></textarea>
                        </div>
                    </div>
                    <?php
                    /*?>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="control-label">Shortcodes Editor</label>
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered">
                                    <tr>
                                        <td>Nombre de Usuario: <span class="label label-danger">{USER_NAME}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Email de Usuario: <span class="label label-danger">{USER_EMAIL}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Rut de Sociedad: <span class="label label-danger">{SOCIETY_RUT}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Nombre de Sociedad: <span class="label label-danger">{SOCIETY_NAME}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dirección de Sociedad: <span
                                                class="label label-danger">{SOCIETY_ADDRESS}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Cta Cte de Sociedad: <span
                                                class="label label-danger">{SOCIETY_CTA_CTE}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php */
                    ?>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="control-label">Mensaje "EXISTE"</label>
                            <textarea class="form-control ckeditor required" name="description3"
                                id="description3"><?php echo $response->description3; ?></textarea>
                        </div>
                    </div>
                    <?php
                    /*?>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="control-label">Shortcodes Editor</label>
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered">
                                    <tr>
                                        <td>Nombre de Usuario: <span class="label label-danger">{USER_NAME}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Email de Usuario: <span class="label label-danger">{USER_EMAIL}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Rut de Sociedad: <span class="label label-danger">{SOCIETY_RUT}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Nombre de Sociedad: <span class="label label-danger">{SOCIETY_NAME}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dirección de Sociedad: <span
                                                class="label label-danger">{SOCIETY_ADDRESS}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Cta Cte de Sociedad: <span
                                                class="label label-danger">{SOCIETY_CTA_CTE}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php */
                    ?>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="control-label">Mensaje "EMAIL NO VÁLIDO"</label>
                            <textarea class="form-control ckeditor required" name="description4"
                                id="description4"><?php echo $response->description4; ?></textarea>
                        </div>
                    </div>
                    <?php
                    /*?>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="control-label">Shortcodes Editor</label>
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered">
                                    <tr>
                                        <td>Nombre de Usuario: <span class="label label-danger">{USER_NAME}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Email de Usuario: <span class="label label-danger">{USER_EMAIL}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Rut de Sociedad: <span class="label label-danger">{SOCIETY_RUT}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Nombre de Sociedad: <span class="label label-danger">{SOCIETY_NAME}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dirección de Sociedad: <span
                                                class="label label-danger">{SOCIETY_ADDRESS}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Cta Cte de Sociedad: <span
                                                class="label label-danger">{SOCIETY_CTA_CTE}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php */
                    ?>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="control-label">¿Activar Registro?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="active" id="active" value="1" <?php if
                                ($response->active == 1) {
                            echo "checked='checked'";
                            } ?>>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>
                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('responses')}}"
                        type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                    <input type="hidden" name="id"
                        value="<?php echo $response->id; ?>" />
                </div>

            </form>

        </div>

    </div>

    <br />
    <x-slot name="js">

        <script type="text/javascript">
            jQuery(document).ready(function($) {

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

        </script>
    </x-slot>
</x-app-layoutt>

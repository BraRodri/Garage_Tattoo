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
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
                Si el error persiste favor comunicarse al administrador.</div>
        @endif
        @if (Session::get('error') == 'duplicate')
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, el registro que intenta
                ingresar ya se encuentra registrado.</div>
        @endif
    @endif

    <div class="panel panel-primary">

        <div class="panel-heading container-blue">
            <div class="panel-title">Formulario de Ingreso</div>
        </div>

        <div class="panel-body color-gris-fondo">

            <form role="form" id="form1" method="post" action="{{route('config.dispatch.insert')}}" enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                @csrf
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="control-label">Descripción</label>
                            <textarea class="form-control required" name="description1" id="description1"></textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="control-label">Precio</label>
                    <input type="number" class="form-control required" name="price" id="price" />
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="control-label">¿Activar Registro?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="active" id="active" value="1" checked="checked">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>
                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('config.dispatch')}}" type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y Volver</a>
                </div>

            </form>

        </div>

    </div>

    <br />
    <x-slot name="js">
        <script type="text/javascript">

            jQuery( document ).ready( function( $ ) {

            $('#form1').validate({
                errorElement: 'span',
                errorClass: 'validate-has-error',
                highlight: function (element) {
                    $(element).closest('.form-group').addClass('validate-has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('validate-has-error');
                },
                errorPlacement: function (error, element)
                {
                    if(element.closest('.has-switch').length)
                    {
                        error.insertAfter(element.closest('.has-switch'));
                    }
                    else
                    if(element.parent('.checkbox, .radio').length || element.parent('.input-group').length)
                    {
                        error.insertAfter(element.parent());
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                }
            });

            });
        </script>
    </x-slot>

</x-app-layoutt>

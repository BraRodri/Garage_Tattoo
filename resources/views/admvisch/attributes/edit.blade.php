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
        @if (Session::get('error') == 'success')
            <div class="alert alert-success"><strong>OK!</strong> Proceso realizado correctamente.</div>
        @endif
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

    <div class="row">

        <div class="col-12 col-lg-4">
            <div class="panel panel-primary">

                <div class="panel-heading container-blue">
                    <div class="panel-title">Formulario de Modificación</div>

                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>

                <div class="panel-body color-gris-fondo">

                    <form role="form" id="form1" method="post" action="{{ route('attributes.update') }}"
                    enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                        @csrf
                        <div class="form-group">
                            <label class="control-label">Título</label>
                            <input type="text" class="form-control required" name="title" id="title" maxlength="255"
                            value="<?php echo $attribute->title; ?>" />
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @php /*
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="row">
                                        <label class="control-label">Descripción</label>
                                        <textarea class="form-control required" name="description1" id="description1">{{$attribute->description}}</textarea>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Valores</label>
                                <input type="text" class="form-control required tagsinput" name="valor" id="valor" value="{{$attribute->values}}" />
                                <label class="formNote">* Para agregar múltiples valores, presionar enter al finalizar
                                    de escribir el valor.</label>
                                <div class="clearfix"></div>
                            </div>
                        */ @endphp

                        <div class="form-group">
                            <label class="control-label">Tipo</label>
                            <select class="form-control" id="tipo" name="tipo">
                                @php $select = $attribute->type; @endphp
                                <option value="Seleccion" @if($select == "Seleccion") selected="" @endif>Selección</option>
                                <option value="Checkbox" @if($select == "Checkbox") selected="" @endif>Checkbox</option>
                            </select>
                            <label class="formNote">* Determina cómo se muestran los valores de este atributo.</label>
                        </div>

                        <div class="form-group">
                            <label class="control-label">¿Activar Registro?</label>
                            <div class="col-md-12 no-padding">
                                <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                    <input type="checkbox" name="active" id="active" value="1" <?php if
                                        ($attribute->active == 1) {
                                    echo "checked='checked'";
                                    } ?>>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>
                        <div class="form-group">
                            <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Actualizar Datos</button>
                            <a href="{{route('attributes')}}"
                                type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                                Volver</a>
                            <input type="hidden" name="id"
                                value="<?php echo $attribute->id; ?>" />
                        </div>

                    </form>

                </div>

            </div>
        </div>

        <div class="col-12 col-lg-8">

            <div class="row">
                <div class="col-lg-6">
                    <h4>Valores del Atributo - <?php echo $attribute->title; ?></h4>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addTodoModal">Agregar Valor</button>
                </div>
            </div>

            <br> <br>

            <div class="table-responsive">
                <table class="table table-bordered datatable order-table" id="table-3">
                    <thead>
                        <tr class="replace-inputs">
                            <th>ID</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Fecha Actualización</th>
                            <th>Modificador</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($values_attributes) > 0) {
                            foreach ($values_attributes as $value_attribute) {

                            $class_status = $value_attribute->active == 1 ? 'success' : 'default';
                            $text_status = $value_attribute->active == 1 ? 'Activo' : 'Inactivo';
                            ?>

                                <tr id="<?php echo $value_attribute->id; ?>">

                                    <td width="5%">#<?php echo $value_attribute->id; ?></td>
                                    <td><?php echo $value_attribute->title; ?></td>
                                    <td width="7%" align="center"><a style="cursor: pointer;" class="change-status"
                                        id="<?php echo $value_attribute->id; ?>"><span
                                            class="badge badge-<?php echo $class_status; ?>"><?php echo $text_status; ?></span></a></td>
                                    <td width="10%"><?php echo Application\Helper::dateFormatUser($value_attribute->updated_at);?></td>
                                    <td width="10%"><?php echo $value_attribute->author; ?></td>
                                    <td width="6%">

                                        <a href="{{ route('attributes.values.delete', $value_attribute->id) }}" class="btn btn-sm btn-danger" alt=""><i class="fa fa-trash-o"></i></a>

                                    </td>

                                </tr>

                            <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="modal" id="addTodoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-body panel-body color-gris-fondo">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h3>Agregar nuevo valor.</h3>

                <form role="form" id="form1" method="post" action="{{route('attributes.values.insert')}}" enctype="multipart/form-data" class=" form-groups-bordered">

                    @csrf
                    <div class="form-group">
                        <label class="control-label">Título</label>
                        <input type="text" class="form-control required" name="title" id="title" maxlength="255" required="" />
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
                    <input type="text" name="id_attribute" value="<?php echo $attribute->id; ?>" hidden>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Agregar</button>
                    </div>

                </form>
            </div>
          </div>
        </div>
    </div>

    <br />
    <x-slot name="js">
        <script type="text/javascript">

            jQuery(document).ready(function($) {
                var $table3 = jQuery("#table-3");

                var table3 = $table3.DataTable({
                    "aLengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "Todos"]
                    ],
                    "pageLength": -1,
                    "oLanguage": language_datatable,
                    //"order": [[9, "desc"]],
                    "ordering": false,
                });
            });

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

            jQuery(document).on("click", ".delete-register", function() {

            var id = jQuery(this).attr('id');

            bootbox.confirm({
                message: "<strong>¿Está seguro que desea eliminar el registro seleccionado?</strong>",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancelar'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirmar',
                        className: 'btn-danger'
                    }
                },
                callback: function(result) {
                    if (result == true) {
                        jQuery(location).attr('href', 'attributes/values/delete/' + id);
                    }
                }
            });
            });

        </script>
    </x-slot>

</x-app-layoutt>

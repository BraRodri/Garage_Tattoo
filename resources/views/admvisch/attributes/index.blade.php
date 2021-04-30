<x-app-layoutt>

    @section('meta')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endsection

    <ol class="breadcrumb bc-2">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li class="active">
            <strong><?php echo $title; ?></strong>
        </li>
    </ol>

    <h3><?php echo $title; ?></h3>
    <br />

    <div class="clearfix"></div>

    @if (Session::has('error'))
        @if (Session::get('error') == 'success')
            <div class="alert alert-success"><strong>OK!</strong> Proceso realizado correctamente.</div>
        @endif
        @if (Session::get('error') == 'failure')
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
                Si el error persiste favor comunicarse al administrador.</div>
        @endif
    @endif

    <div class="row">
        <div class="col-12 col-lg-4">

            <div class="panel panel-primary">

                <div class="panel-heading container-blue">
                    <div class="panel-title">Formulario de Ingreso</div>
                </div>

                <div class="panel-body color-gris-fondo">

                    <form role="form" id="form1" method="post" action="{{route('attributes.insert')}}" enctype="multipart/form-data" class=" form-groups-bordered">

                        @csrf
                        <div class="form-group">
                            <label class="control-label">Título</label>
                            <input type="text" class="form-control required" name="title" id="title" maxlength="255" required="" />
                        </div>

                        @php /*
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
                                <label class="control-label">Valores</label>
                                <input type="text" class="form-control required tagsinput" name="valor" id="valor" />
                                <label class="formNote">* Para agregar múltiples valores, presionar enter al finalizar
                                    de escribir el valor.</label>
                                <div class="clearfix"></div>
                            </div>
                        */ @endphp

                        <div class="form-group">
                            <label class="control-label">Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" required="">
                                <option value="Seleccion">Selección</option>
                                <option value="Checkbox">Checkbox</option>
                            </select>
                            <label class="formNote">* Determina cómo se muestran los valores de este atributo.</label>
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
                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-12 col-lg-8">
            <div class="table-responsive">
                <table class="table table-bordered datatable order-table" id="table-3">
                    <thead>
                        <tr class="replace-inputs">
                            <th>ID</th>
                            <th>Titulo</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Fecha Actualización</th>
                            <th>Modificador</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($attributes) > 0) {
                        foreach ($attributes as $attribute) {

                        $class_status = $attribute->active == 1 ? 'success' : 'default';
                        $text_status = $attribute->active == 1 ? 'Activo' : 'Inactivo';
                        ?>

                            <tr id="<?php echo $attribute->id; ?>">

                                <td width="5%">#<?php echo $attribute->id; ?></td>
                                <td><?php echo $attribute->title; ?></td>
                                <td><?php echo $attribute->type; ?></td>
                                <td width="7%" align="center"><a style="cursor: pointer;" class="change-status"
                                    id="<?php echo $attribute->id; ?>"><span
                                        class="badge badge-<?php echo $class_status; ?>"><?php echo $text_status; ?></span></a></td>
                                <td width="10%"><?php echo Application\Helper::dateFormatUser($attribute->updated_at);?></td>
                                <td width="10%"><?php echo $attribute->author; ?></td>
                                <td width="6%">

                                    <a type="button" class="btn btn-sm btn-gold" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Editar" href="{{ route('attributes.edit', $attribute->id) }}"><i
                                            class="fa fa fa-pencil-square-o"></i></a>


                                    <a type="button" class="btn btn-sm btn-danger delete-register" data-toggle="tooltip"
                                        data-placement="top" title="" data-original-title="Eliminar"
                                        id="<?php echo $attribute->id; ?>"><i
                                            class="fa fa-trash-o"></i></a>

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

    <input type="text" value="{{csrf_token()}}" name="_token" hidden>
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

                // Initalize Select Dropdown after DataTables is created
                $table3.closest('.dataTables_wrapper').find('select').addClass('form-control');
                $('.dataTables_length label select').appendTo('.dataTables_length');

                var startPosition;
                var endPosition;
                var id;
                var data;
                var url;

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
                        jQuery(location).attr('href', 'attributes/delete/' + id);
                    }
                }
            });
            });

            jQuery(document).on("click", ".change-status", function() {

            var $element = jQuery(this);
            var id = $element.attr('id');
            var url = "{{route('attributes.status')}}";
            var data = {
                module_name: "{{$module}}",
                id: id
            }

            jQuery.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                encoding: "UTF-8",
                url: url,
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        $element.find('span').removeAttr('class').attr('class', '');
                        $element.find('span').addClass('badge');
                        $element.find('span').addClass(response.class_status);
                        $element.find('span').text(response.text_status);
                    }
                }
            });
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

</x-app-layoutt>

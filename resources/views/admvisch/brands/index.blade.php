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
        <li class="active">
            <strong><?php echo $title; ?></strong>
        </li>
    </ol>

    <h3><?php echo $title; ?></h3>
    <br />

    <div class="clearfix"></div>


    @can('brands.agregar')
    <div class="form-group">
        <a href="{{ route('brands.enter') }}" type="button" class="btn btn-blue"><i class="fa fa-plus"></i> Nuevo</a>
    </div>
    @endcan
    
    @if (Session::has('error'))
        @if (Session::get('error') == 'success')
            <div class="alert alert-success"><strong>OK!</strong> Proceso realizado correctamente.</div>
        @endif
        @if (Session::get('error') == 'failure')
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
                Si el error persiste favor comunicarse al administrador.</div>
        @endif
    @endif

    <div class="table-responsive">
        <table class="table table-bordered datatable" id="table-3">
            <thead>
                <tr class="replace-inputs">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Fecha Actualización</th>
                    <th>Modificador</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($brands) > 0) {
                foreach ($brands as $brand) {

                $class_status = $brand->active == 1 ? 'success' : 'default';
                $text_status = $brand->active == 1 ? 'Activo' : 'Inactivo';
                ?>
                <tr>
                    <td width="5%">#<?php echo $brand->id; ?></td>
                    <td><?php echo $brand->title; ?></td>
                    <td width="7%" align="center"><a style="cursor: pointer;" class="change-status"
                            id="<?php echo $brand->id; ?>"><span
                                class="badge badge-<?php echo $class_status; ?>"><?php echo $text_status; ?></span></a></td>
                    <td width="10%"><?php echo Application\Helper::dateFormatUser($brand->updated_date);
                        ?></td>
                    <td width="10%"><?php echo $brand->author; ?></td>
                    <td width="6%">

                        @can('brands.editar')
                        <a type="button" class="btn btn-sm btn-gold" data-toggle="tooltip" data-placement="top" title=""
                        data-original-title="Editar" href="{{ route('brands.edit', $brand->id) }}"><i
                            class="fa fa fa-pencil-square-o"></i></a>
                        @endcan
 
                        @can('brands.eliminar', Model::class)
                        <a type="button" class="btn btn-sm btn-danger delete-register" data-toggle="tooltip"
                        data-placement="top" title="" data-original-title="Eliminar"
                        id="<?php echo $brand->id; ?>"><i
                            class="fa fa-trash-o"></i></a>
                        @endcan
                        

                    </td>
                </tr>
                <?php
                }
                } ?>
            </tbody>
        </table>
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
                    "oLanguage": language_datatable,
                    //"order": [[9, "desc"]],
                    "ordering": false,
                });

                // Initalize Select Dropdown after DataTables is created
                $table3.closest('.dataTables_wrapper').find('select').addClass('form-control');
                $('.dataTables_length label select').appendTo('.dataTables_length');
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
                            jQuery(location).attr('href', 'roles/delete/' + id);
                        }
                    }
                });
            });

            jQuery(document).on("click", ".change-status", function() {

                var $element = jQuery(this);
                var id = $element.attr('id');
                var url = "{{ route('brands.status') }}";
                var data = {
                    module_name: "{{ $module }}",
                    id: id
                }

                jQuery.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
    </x-slot>
</x-app-layoutt>

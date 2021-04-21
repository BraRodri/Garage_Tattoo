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

    <div class="form-group">

        @can('products.agregar')
        <a href="{{route('products.enter')}}" type="button"
        class="btn btn-blue"><i class="fa fa-plus"></i> Nuevo</a>
        @endcan
        
        <a href="{{route('products.export',1)}}"
        type="button" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Exportar</a>
        
        <a href="{{route('products.exportGalleries',1)}}"
        type="button" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Exportar
        Imágenes</a>
       
        
    </div>

    @if (Session::has('error'))
        @if (Session::get('error') == 'success')
            <div class="alert alert-success"><strong>OK!</strong> Proceso realizado correctamente.</div>
        @endif
        @if (Session::get('error') == 'failure')
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
                Si el error persiste favor comunicarse al administrador.</div>
        @endif
    @endif

    <hr>

    <div class="row" style="margin-top:20px;margin-bottom:20px">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Estado</label>
                <select class="form-control" name="active" id="active">
                    <option value="">Todos</option>
                    <option value="A">Activos</option>
                    <option value="I">Inactivos</option>
                </select>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Stock</label>
                <select class="form-control" name="stock" id="stock">
                    <option value="">Todos</option>
                    <option value="C">Con Stock</option>
                    <option value="S">Sin Stock</option>
                </select>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Categoría</label>
                <select class="form-control" name="category" id="category">
                    <option value=""></option>
                    <?php echo $categories; ?>
                </select>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label">Marca</label>
                <select class="form-control" name="brand" id="brand">
                    <option value=""></option>
                    <?php echo $brands; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered datatable" id="table-fixed">
            <thead>
                <tr class="replace-inputs">
                    <th width="5%">Cód.</th>
                    <th>Nombre</th>
                    <th width="27%">Jeraquía</th>
                    <th width="5%">Stock</th>
                    <th width="7%">Estado</th>
                    <th width="10%">Fecha Actualización</th>
                    <th width="10%">Modificador</th>
                    <th width="6%"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <br />

    <x-slot name="js">
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $.fn.loadDataTableDocuments = function() {

                    $('body,html').animate({
                        scrollTop: 0
                    }, 300);
                    $("body").prepend(contenido_loader);

                    var url = "{{route('products.documents')}}";
                    var data = {
                        active: $('[name=active]').find(':selected').val(),
                        stock: $('[name=stock]').find(':selected').val(),
                        category: $('[name=category]').find(':selected').val(),
                        brand: $('[name=brand]').find(':selected').val()
                    }

                    var $table_fixed = $("#table-fixed");

                    var table_fixed = $table_fixed.DataTable({
                        "ajax": {

                            "url": url,
                            "type": "POST",
                            "data": data,
                            "headers": {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        },
                        "ordering": false,
                        "lengthMenu": [
                            [10, 50, 100, 150, -1],
                            [10, 50, 100, 150, "All"]
                        ],
                        "pageLength": 50,
                        "oLanguage": language_datatable,
                        "columnDefs": [{
                                targets: 3,
                                className: 'cell-right'
                            },
                            {
                                targets: 4,
                                className: 'cell-center'
                            },

                        ],
                        "initComplete": function(settings, json) {
                            $('#wraper_ajax').remove();

                            // Initalize Select Dropdown after DataTables is created
                            $table_fixed.closest('.dataTables_wrapper').find('select').addClass(
                                'form-control');
                            $('.dataTables_length label select').appendTo('.dataTables_length');
                        }
                    });
                };

                $('body').loadDataTableDocuments();

            });

            jQuery(document).on("change",
                "select[name=active],select[name=stock],select[name=category],select[name=brand]",
                function() {
                    if ($.fn.DataTable.isDataTable('#table-fixed')) {
                        $('#table-fixed').DataTable().clear();
                        $('#table-fixed').DataTable().destroy();
                    }
                    $('body').loadDataTableDocuments();
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
                            jQuery(location).attr('href', 'products/delete/' + id);
                        }
                    }
                });
            });

            jQuery(document).on("click", ".change-status", function() {

                var $element = jQuery(this);
                var id = $element.attr('id');
                var url = "{{route('products.status')}}";
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
    </x-slot>
</x-app-layoutt>

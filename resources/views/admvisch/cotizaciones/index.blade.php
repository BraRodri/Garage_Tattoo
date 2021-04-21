<x-app-layoutt>


    <?php if (isset($error)) {
    if ($error == 'failure') {
    echo '<div class="alert alert-danger"><strong>ERROR!</strong> No se ha encontrado cliente bajo búsqueda realizada,
        favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
    }
    } ?>

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

    <form role="form" id="form1" method="post"
        action="{{route('cotizaciones')}}"
        enctype="multipart/form-data">

        @csrf
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-primary">
                    <div class="panel-heading container-blue">
                        <div class="panel-title"><i class="fa fa-search"></i> Filtros de Búsqueda</div>

                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        </div>
                    </div>

                    <div class="panel-body with-table">

                        <div class="row bs-callout bs-callout-danger">

                            <div class="col-md-3">
                                <span class="control-label">Fecha Desde</span><br>
                                <div class="col-md-6 no-padding">
                                    <div class="input-group">
                                        <input type="text" name="date_start_document" id="date_start_document"
                                            class="form-control datepicker" data-format="dd-mm-yyyy"
                                            value="<?php echo $_date_start_document; ?>">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="entypo-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <span class="control-label">Fecha Hasta</span><br>
                                <div class="col-md-6 no-padding">
                                    <div class="input-group">
                                        <input type="text" name="date_end_document" id="date_end_document"
                                            class="form-control datepicker" data-format="dd-mm-yyyy"
                                            value="<?php echo $_date_end_document; ?>">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="entypo-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <span class="control-label">&nbsp;</span><br>
                                <div class="col-md-6 no-padding" style="margin:0 0 10px 0">
                                    <button type="submit" class="btn btn-blue"><i class="fa fa-search"></i>
                                        Buscar</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {

                jQuery('.multiselect').multiselect({
                    nonSelectedText: 'No seleccionado',
                    nSelectedText: 'Seleccionado',
                    allSelectedText: 'Todos seleccionados',
                    numberDisplayed: 1,
                    includeFilterClearBtn: true,
                    buttonClass: 'btn btn-white',
                    maxHeight: 300,
                    includeSelectAllOption: true,
                    selectAllText: 'Seleccionar Todos',
                });
            });

        </script>

        <div class="row">
            <div class="col-md-12 container-button-gestion">
                <button type="button" class="btn btn-success" id="export-documents"><i
                        class="glyphicon glyphicon-cloud-download"></i> Exportar</button>
                <button type="button" class="btn btn-success" id="export-documents-selected"><i
                        class="glyphicon glyphicon-cloud-download"></i> Exportar Seleccionados</button>
            </div>
        </div>

        <div class="clearfix"></div>

        <hr>

        <div class="row">

            <div class="col-md-12">

                <table class="table table-bordered table-hover" id="table-fixed">
                    <thead>
                        <tr>
                            <th style="text-align:center;">
                                <input type="checkbox" id="check-all" value="1">
                            </th>
                            <th class="text-negrita"></th>
                            <th class="text-negrita"></th>
                            <th class="text-negrita">N°</th>
                            <th class="text-negrita">Fecha</th>
                            <th class="text-negrita">Rut</th>
                            <th class="text-negrita">Nombre</th>
                            <th class="text-negrita">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>


        </div>

    </form>

    <div class="clearfix"></div>

    <br />

    <div class="modal fade modal-super" id="modal-compact-view">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Detalle de Cotización</h4>
                </div>

                <div class="modal-body"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




    <x-slot name="js">
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $.fn.loadDataTableDocuments = function() {

                    $('body,html').animate({
                        scrollTop: 0
                    }, 300);
                    $("body").prepend(contenido_loader);

                    var data = {
                        date_start_document: $("input#date_start_document").val(),
                        date_end_document: $("input#date_end_document").val(),
                    }

                    var url = "{{route('cotizaciones.documents')}}";

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
                        "scrollY": 400,
                        "scrollX": true,
                        "bSortCellsTop": true,
                        "bScrollCollapse": true,
                        "order": [
                            [1, "desc"]
                        ],
                        "lengthMenu": [
                            [50, 100, 150, -1],
                            [50, 100, 150, "All"]
                        ],
                        "pageLength": 100,
                        "oLanguage": language_datatable,
                        "columnDefs": [{
                                targets: 0,
                                className: 'cell-center'
                            },
                            {
                                targets: 1,
                                className: 'cell-center'
                            },
                            {
                                targets: 2,
                                className: 'cell-center'
                            },
                            {
                                targets: 3,
                                className: 'cell-center'
                            },
                            {
                                targets: 7,
                                className: 'cell-right'
                            }
                        ],
                        "initComplete": function(settings, json) {
                            $('#wraper_ajax').remove();
                        }
                    });
                };

                $('body').loadDataTableDocuments();

                $('#export-documents').click(function() {
                    $('#form1').attr('action',"{{route('cotizaciones.export')}}");
                    $('#form1').submit();
                    $('#form1').attr('action', "{{route('cotizaciones')}}");
                });

                $('#export-documents-selected').click(function() {
                    $('#form1').attr('action', "{{route('cotizaciones.export','1')}}");
                    $('#form1').submit();
                    $('#form1').attr('action', "{{route('cotizaciones')}}");
                });
            });

            jQuery(document).on('change', '#check-all', function() {
                var checkboxes = jQuery('#table-fixed tbody').find(':checkbox');

                if (jQuery(this).is(':checked')) {
                    checkboxes.prop('checked', true);
                    checkboxes.parent().parent().addClass('check-selected');
                } else {
                    checkboxes.prop('checked', false);
                    checkboxes.parent().parent().removeClass('check-selected');
                }
            });

            jQuery(document).on('click', '.show-compact-view', function() {

                var url = "{{route('cotizaciones.viewCompact')}}";
                var data = {
                    id: jQuery(this).attr('id')
                }

                $.ajax({
                    type: "POST",
                    encoding: "UTF-8",
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $('#wraper_ajax').remove();

                        jQuery('body').addClass('modal-open-super');
                        jQuery('#modal-compact-view').modal('show', {
                            backdrop: 'static'
                        });
                        jQuery('#modal-compact-view .modal-body').html(response.data);
                    }
                });
            });

        </script>
    </x-slot>



</x-app-layoutt>

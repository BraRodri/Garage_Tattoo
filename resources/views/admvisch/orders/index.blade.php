<x-app-layoutt>

    @section('meta')
        <meta name="csrf-token" content="{{ csrf_token() }}">

    @endsection
    @if (Session::has('error'))
    @if (Session::get('error') == 'success')
        <div class="alert alert-success"><strong>OK!</strong> Proceso realizado correctamente.</div>
    @endif
    @if (Session::get('error') == 'failure')
        <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
            Si el error persiste favor comunicarse al administrador.</div>
    @endif
@endif

    <ol class="breadcrumb bc-2">
        <li>
            <a href=""><i class="entypo-home"></i>Home</a>
        </li>
        <li class="active">
            <strong>{{ $title }}</strong>
        </li>
    </ol>

    <h3>{{ $title }}</h3>
    <br />

    <form role="form" id="form1" method="post" action="{{ route('orders') }}" enctype="multipart/form-data">

        @csrf
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="row">

            <div class="col-sm-3 col-xs-6">

                <div class="tile-stats tile-blue">
                    <div class="icon" style="bottom:37px;"><i class="fa fa-money"></i></div>
                    <div class="num num-ventas" data-start="0" data-end="" data-postfix="" data-duration="1500"
                        data-delay="600">$0</div>

                    <h3>Total Pedidos</h3>
                </div>

            </div>

            <div class="col-sm-3 col-xs-6">

                <div class="tile-stats tile-gray">
                    <div class="icon"><i class="entypo-chart-bar"></i></div>
                    <div class="num num-pendientes" data-start="0" data-end="0" data-postfix="" data-duration="1500"
                        data-delay="600">0</div>

                    <h3>Pedidos Pendientes</h3>
                </div>

            </div>

            <div class="col-sm-3 col-xs-6">

                <div class="tile-stats tile-green">
                    <div class="icon"><i class="entypo-chart-bar"></i></div>
                    <div class="num num-aprobadas" data-start="0" data-end="0" data-postfix="" data-duration="1500"
                        data-delay="600">0</div>

                    <h3>Pedidos Aprobados</h3>
                </div>

            </div>

            <div class="clear visible-xs"></div>

            <div class="col-sm-3 col-xs-6">

                <div class="tile-stats tile-red">
                    <div class="icon"><i class="entypo-block"></i></div>
                    <div class="num num-rechazadas" data-start="0" data-end="0" data-postfix="" data-duration="1500"
                        data-delay="600">0</div>

                    <h3>Pedidos Rechazados</h3>
                </div>

            </div>

        </div>

        <hr />

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
                                            value="{{ $_date_start_document }}">
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
                                            value="{{ $_date_end_document }}">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="entypo-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <span class="control-label">Estado Pago</span><br>
                                <div class="col-md-6 no-padding" style="margin:0 0 10px 0">
                                    <div class="input-group">
                                        <select class="form-control required" name="status_payment" id="status_payment">
                                            <option value="">Seleccionar</option>


                                            @foreach ($status_payment as $key)

                                                {{ $selected = isset($_status_payment) && in_array($key, $_status_payment) ? 'selected="selected"' : '' }}

                                                <option value="{{ $key }}" {{ $selected }}>
                                                    {{ $key }}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <span class="control-label">Estado Despacho</span><br>
                                <div class="col-md-6 no-padding" style="margin:0 0 10px 0">
                                    <div class="input-group">
                                        <select class="form-control required" name="status_shipping"
                                            id="status_shipping">
                                            <option value="">Seleccionar</option>


                                            @foreach ($status_shipping as $key)
                                                {{ $selected = isset($_status_shipping) && in_array($key, $_status_shipping) ? 'selected="selected"' : '' }}
                                                <option value="{{ $key }}" {{ $selected }}>
                                                    {{ $key }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="col-md-3">
                                <span class="control-label">Sucursal</span><br>
                                <div class="col-md-6 no-padding" style="margin:0 0 10px 0">
                                    <div class="input-group">
                                        <select class="form-control required" name="offices" id="offices">
                                            <option value="">Todas</option>

                                            @if ($offices->count() > 0)

                                                @foreach ($offices as $office)
                                                    {{ $selected = isset($_offices) && in_array($office->id, $_offices) ? 'selected="selected"' : '' }}
                                                    <option value="{{ $office->id }}" {{ $selected }}>
                                                        {{ $office->title }} . ' ' . {{ $office->description }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
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
                            <th class="text-negrita">Sucursal</th>
                            <th class="text-negrita">Rut</th>
                            <th class="text-negrita">Cliente</th>
                            <th class="text-negrita">Forma Pago</th>
                            <th class="text-negrita">Estado</th>
                            <th class="text-negrita">Despacho</th>
                            <th class="text-negrita">Estado Despacho</th>
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
                    <h4 class="modal-title">Detalle de Orden de Compra</h4>
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

                
                $.fn.loadDataTableDocuments = function() {

                    $('body,html').animate({
                        scrollTop: 0
                    }, 300);
                    $("body").prepend(contenido_loader);

                    var status_payment = '';
                    $('[name="status_payment"]').find(':selected').each(function() {
                        if ($(this).val() != '') {
                            status_payment += $(this).val() + '^';
                        }
                    });

                    var status_shipping = '';
                    $('[name="status_shipping"]').find(':selected').each(function() {
                        if ($(this).val() != '') {
                            status_shipping += $(this).val() + '^';
                        }
                    });

                    var offices = '';
                    $('[name="offices"]').find(':selected').each(function() {
                        if ($(this).val() != '') {
                            offices += $(this).val() + '^';
                        }
                    });

                    var data = {
                        date_start_document: $("input#date_start_document").val(),
                        date_end_document: $("input#date_end_document").val(),
                        status_payment: status_payment,
                        status_shipping: status_shipping,
                        offices: offices
                    }

                    var url = "{{ route('orders.documents') }}";

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
                                targets: 8,
                                className: 'cell-center'
                            },
                            {
                                targets: 9,
                                className: 'cell-center'
                            },
                            {
                                targets: 10,
                                className: 'cell-center'
                            },
                            {
                                targets: 11,
                                className: 'cell-center'
                            },
                            {
                                targets: 12,
                                className: 'cell-right'
                            }
                        ],
                        "initComplete": function(settings, json) {
                            $('.num-ventas').attr('data-end', reemplazar(json.total_ventas, '.',
                                ''));
                            $('.num-pendientes').attr('data-end', json.num_pendientes);
                            $('.num-aprobadas').attr('data-end', json.num_aprobadas);
                            $('.num-rechazadas').attr('data-end', json.num_rechazadas);

                            $('.num-ventas').html('$' + json.total_ventas);
                            $('.num-pendientes').html(json.num_pendientes);
                            $('.num-aprobadas').html(json.num_aprobadas);
                            $('.num-rechazadas').html(json.num_rechazadas);


                            $('#wraper_ajax').remove();
                           
                        }


                    });

                    console.log("HACIENDO TABLAS");
                };



                $('body').loadDataTableDocuments();

                $('#export-documents').click(function() {
                   
                    $('#form1').attr('action', "{{ route('orders.export') }}");
                    $('#form1').submit();
                    $('#form1').attr('action', "{{ route('orders') }}");

                    console.log("EXPORTANDO");
                });

                $('#export-documents-selected').click(function() {
                    $('#form1').attr('action', '/orders/export/1');
                    $('#form1').submit();
                    $('#form1').attr('action', "{{ route('orders') }}");

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

                var url = "{{ route('orders.viewCompact') }}";
                var data = {
                    id: jQuery(this).attr('id')
                }

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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

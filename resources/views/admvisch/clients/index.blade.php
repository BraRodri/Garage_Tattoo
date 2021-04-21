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
        @can('clients.agregar')
            <a href="{{ route('clients.enter') }}" type="button" class="btn btn-blue"><i class="fa fa-plus"></i> Nuevo</a>
        @endcan
        
        <br>
        <br>

        @can('clients.import')
        <form action="{{ route('clients.export', 1) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i>
                Exportar</button>

        </form>  
        @endcan
        

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


    <div class="table-responsive">
        <table class="table table-bordered datatable" id="table-fixed">
            <thead>
                <tr class="replace-inputs">
                    <th width="5%">ID</th>
                    <th width="7%">Rut</th>
                    <th>Razón Social</th>
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

    <input type="text" value="{{csrf_token()}}" name="_token" hidden>

    <br />
    <x-slot name="js">

        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $.fn.loadDataTableDocuments = function() {

                    $('body,html').animate({
                        scrollTop: 0
                    }, 300);
                    $("body").prepend(contenido_loader);

                    var url = "{{ route('clients.documents') }}";
                    var data = {}

                    var $table_fixed = $("#table-fixed");

                    var table_fixed = $table_fixed.DataTable({
                        "ajax": {
                            "headers": { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            "url": url,
                            "type": "POST",
                            "data": data
                        },
                        "ordering": false,
                        "lengthMenu": [
                            [50, 100, 150, -1],
                            [50, 100, 150, "All"]
                        ],
                        "pageLength": 50,
                        "oLanguage": language_datatable,
                        "columnDefs": [{
                            targets: 3,
                            className: 'cell-center'
                        }, ],
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
                            jQuery(location).attr('href', 'clients/delete/' + id);
                        }
                    }
                });
            });

            jQuery(document).on("click", ".change-status", function() {

                var $element = jQuery(this);
                var id = $element.attr('id');
                var url = "{{route('clients.status')}}";
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

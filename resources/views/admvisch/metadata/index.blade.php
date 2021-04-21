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
                    <th>Título</th>
                    <th>Fecha Actualización</th>
                    <th>Modificador</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($metadata) > 0) {
                foreach ($metadata as $meta) { ?>
                <tr>
                    <td width="5%">#<?php echo $meta->id; ?></td>
                    <td><?php echo $meta->title; ?></td>
                    <td width="10%"><?php echo Application\Helper::dateFormatUser($meta->updated_date);
                        ?></td>
                    <td width="10%"><?php echo $meta->author; ?></td>
                    <td width="6%">

                        <a type="button" class="btn btn-sm btn-gold" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Editar" href="{{ route('metadata.edit', $meta->id) }}"><i
                                class="fa fa fa-pencil-square-o"></i></a>


                    </td>
                </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </div>
    <input type="text" value="{{ csrf_token() }}" name="_token" hidden>

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

                // Setup - add a text input to each footer cell
                $('#table-3 tfoot th.search-footer').each(function() {
                    var title = $('#table-3 thead th').eq($(this).index()).text();
                    $(this).html('<input type="text" class="form-control" placeholder="Buscar ' +
                        title + '" />');
                });

                // Apply the search
                table3.columns().every(function() {
                    var that = this;

                    $('input', this.footer()).on('keyup change', function() {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });

            });

        </script>
    </x-slot>
</x-app-layoutt>

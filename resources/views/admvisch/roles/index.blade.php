<x-app-layoutt>

    <ol class="breadcrumb bc-2" >
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
    

    @can('roles.agregar')
    <div class="form-group">
        <a href="{{route('roles.create')}}" type="button" class="btn btn-blue"><i class="fa fa-plus"></i> Nuevo</a>
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
                <th>Id</th>
                <th>Rol</th>
                
            </tr>
            </thead>
            <tbody>
            <?php
            if(count($roles) > 0) {
                foreach ($roles AS $rol) {
                    ?>
                    <tr>
                        <td><?php echo $rol->id; ?></td>
                        <td><?php echo $rol->name; ?></td>
        
                        <td width="6%">
                           
                            @can('roles.editar')
                            <a type="button" class="btn btn-sm btn-gold" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" href="{{route('roles.edit',$rol->id)}}"><i class="fa fa fa-pencil-square-o"></i></a>
                            @endcan

                            @can('roles.eliminar', Model::class)
                            <a type="button" class="btn btn-sm btn-danger delete-register" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" id="<?php echo $rol->id; ?>"><i class="fa fa-trash-o"></i></a>      
                            @endcan
                           
                
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    
    <br />
    <x-slot name="js">
        <script type="text/javascript">
jQuery( document ).ready( function( $ ) {
    var $table3 = jQuery("#table-3");

    var table3 = $table3.DataTable( {
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        "oLanguage": language_datatable
    });

    // Initalize Select Dropdown after DataTables is created
    $table3.closest( '.dataTables_wrapper' ).find( 'select' ).addClass('form-control');
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
        callback: function (result) {
            if(result == true) {
                jQuery(location).attr('href', 'roles/delete/' + id);
            }
        }
    });
});


        </script>
    </x-slot>
</x-app-layoutt>
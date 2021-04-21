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
        <li>
            <a href="{{route('products.import')}}"><?php echo $title; ?></a>
        </li>
        <li class="active">
            <strong>Importación Masiva</strong>
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
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, el slider que intenta
                ingresar ya se encuentra registrado.</div>
        @endif
    @endif

    <div class="panel panel-primary">

        <div class="panel-heading container-blue">
            <div class="panel-title">Formulario de Importación</div>

            <div class="panel-options">
                <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
            </div>
        </div>

        <div class="panel-body color-gris-fondo">

            <form role="form" id="form1" method="post"
                action="{{route('products.upload')}}"
                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                @csrf
                <div class="form-group">
                    <label class="control-label">Excel</label>
                    <div class="clearfix"></div>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <span class="btn btn-info btn-file">
                            <span class="fileinput-new">Seleccionar Excel</span>
                            <span class="fileinput-exists">Cambiar</span>
                            <input type="file" name="archivo">
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                            style="float: none">&times;</a>
                    </div>

                    <ul>
                        <li class="text-danger-red">Tamaño máximo de carga <?php echo
                            Application\Helper::uploadSizeUser(); ?>.</li>
                        <li class="text-danger-red">Para importar correctamente el archivo, descarga el formato <a
                                href="{{route('products.export')}}">aquí</a>.
                        </li>
                    </ul>

                    <ul>
                        <li class="text-danger-red">Recuerde no alterar las columnas, solo actualice las celdas.</li>
                        <li class="text-danger-red">
                            Las <strong>&quot;Celdas&quot;</strong> no deben contener:
                            <ul>
                                <li>Espacios</li>
                                <li>Formatos de Números o Letras</li>
                                <li>Formulas o Funciones</li>
                            </ul>
                        </li>
                        <li class="text-danger-red">Respete acentos, espacios, mayúscula y minúscula en contenidos
                            relacionados como por ejemplo; &quot;<strong>Categorías y Marcas</strong>&quot;. Ya que al
                            indicarlos mal se insertara un nuevo registro.</li>
                        <li class="text-danger-red">Recuerde que el nombre de la &quot;<strong>Ficha PDF</strong>
                            indicado en excel, debe coincidir con el nombre del archivo, respetando mayúsculas y
                            minúsculas. Sugerencia: no incluir espacios y caracteres especiales. Además revisar la
                            extensión.</li>
                        <li class="text-danger-red">Sólo se permite libro de <strong>excel 2007+</strong> cuya extensión
                            es <strong>.xlsx</strong></li>
                    </ul>

                    <ul>
                        <li class="text-danger-red">Antes de importar el excel, cargue las fichas pdf en: "<strong><?php echo UPLOAD_URL_ROOT . $module . DS . 'pdf'; ?></strong>". Este procedimiento ayudará a comprobar si coincide el nombre especificado
                            con el archivo.</li>
                    </ul>

                </div>

                <div class="form-group">
                    <div class="progress progress-striped active" style="border:1px solid #ccc">
                        <div class="progress-bar progress-bar-success myprogress" role="progressbar" aria-valuenow="0"
                            aria-valuemin="0" aria-valuemax="100" style="width:0%">
                            <span class="sr-only" style="position:relative;">0% Completado</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-blue" id="import-file"><i class="fa fa-exchange"></i> Procesar
                        Excel</button>
                </div>

            </form>

        </div>

    </div>

    <br />

    <div class="modal fade" id="modalMessageImport">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Detalle de Importación Masiva</h4>
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

                $('#modalMessageImport').on('hide.bs.modal', function() {
                    $(location).attr('href',"{{route('products.import')}}");
                });

                $('#import-file').click(function() {

                    var url = "{{route('products.upload')}}";
                    var formData = new FormData();
                    formData.append('archivo', $('input[type="file"]')[0].files[0]);

                    $('#import-file').attr('disabled', 'disabled');

                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        type: "POST",
                        encoding: "UTF-8",
                        url: url,
                        data: formData,
                        processData: false, // tell jQuery not to process the data
                        contentType: false, // tell jQuery not to set contentType
                        dataType: 'json',
                        success: function(response) {
                            if (response.error == 0) {
                                $('body').loadProductsByExcel(response.archiveName);
                            } else {
                                bootbox.alert({
                                    message: "<strong>ERROR: " + response.message +
                                        "</strong>",
                                    closeButton: false,
                                    buttons: {
                                        ok: {
                                            label: 'Aceptar',
                                            className: 'btn-red'
                                        }
                                    },
                                    callback: function(result) {
                                        $(location).attr('href',"{{route('products.import')}}");
                                    }
                                });
                            }
                        },
                        error: function() {
                            bootbox.alert({
                                message: "<strong>ERROR: No se ha logrado obtener una respuesta correcta.</strong>",
                                closeButton: false,
                                buttons: {
                                    ok: {
                                        label: 'Aceptar',
                                        className: 'btn-red'
                                    }
                                },
                                callback: function(result) {
                                    $(location).attr('href',"{{route('products.import')}}");
                                }
                            });
                        }
                    });
                });

                $.fn.loadProductsByExcel = function(archiveName) {

                    var url ="{{route('products.loadProductsByExcel')}}";
                    var data = {
                        archiveName: archiveName
                    }

                    var idIntervalGlobal = window.setInterval(1, 1000);

                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        type: "POST",
                        encoding: "UTF-8",
                        url: url,
                        data: data,
                        dataType: 'json',
                        beforeSend: function() {
                            var type_send = 1;
                            var percentage = 0;
                            var timer = setInterval(function() {
                                percentage = percentage + 1;
                                if (percentage <= 80) {
                                    progress_bar_process(percentage, timer, type_send,
                                        idIntervalGlobal);
                                }
                            }, 1000);
                        },
                        success: function(response) {
                            $('#wraper_ajax').remove();

                            var type_send = 2;
                            var percentage = 80;
                            var timer = setInterval(function() {
                                percentage = percentage + 1;
                                if (percentage <= 100) {
                                    progress_bar_process(percentage, timer, type_send,
                                        idIntervalGlobal);
                                }
                                if (percentage == 100) {
                                    if (response.error != 0) {
                                        bootbox.alert({
                                            message: "<strong>" + response
                                                .message + "</strong>",
                                            closeButton: false,
                                            buttons: {
                                                ok: {
                                                    label: 'Aceptar',
                                                    className: 'btn-red'
                                                }
                                            },
                                            callback: function(result) {
                                                $(location).attr('href',"{{route('products.import')}}");
                                            }
                                        });
                                    } else {
                                        var content_general = '';

                                        content_general += '<div class="row">';
                                        content_general += '<ul>';
                                        content_general +=
                                            '<li>Total Registros: <strong>' + response
                                            .number_total + '</strong></li>';
                                        content_general +=
                                            '<li>Total Insertados: <strong>' + response
                                            .number_insert + '</strong></li>';
                                        content_general +=
                                            '<li>Total Actualizados: <strong>' +
                                            response.number_update + '</strong></li>';
                                        content_general +=
                                            '<li style="list-style:none">&nbsp;</li>';
                                        content_general +=
                                            '<li>Archivos Encontrados <strong>' +
                                            response.number_archive_found +
                                            '</strong></li>';
                                        content_general +=
                                            '<li>Archivos No Encontrados <strong>' +
                                            response.number_archive_not_found +
                                            '</strong></li>';
                                        content_general += '</ul>';
                                        content_general += '</div>';

                                        jQuery('#modalMessageImport').modal('show', {
                                            backdrop: 'static'
                                        });
                                        jQuery('#modalMessageImport .modal-body')
                                            .append(content_general);

                                        /*******************************************************************************************************/

                                        var content = '';

                                        content += '<hr />';
                                        content +=
                                            '<p><strong>Resultado General</strong></p>';
                                        content += '<div class="table-responsive">';
                                        content +=
                                            '<table class="table table-bordered">';
                                        content += '<thead>';
                                        content += '<tr>';
                                        content += '<th align="left">Código</th>';
                                        content += '<th align="left">Descripción</th>';
                                        content += '<th align="left">Línea Excel</th>';
                                        content += '<th align="left">Mensaje</th>';
                                        content += '</tr>';
                                        content += '</thead>';
                                        content += '<tbody>';

                                        if ($(response.response_data).size() > 0) {
                                            $.each(response.response_data, function(i,
                                                post) {
                                                if (response.response_data[i]
                                                    .code != null || response
                                                    .response_data[i].code !=
                                                    undefined) {
                                                    content += '<tr>';
                                                    content +=
                                                        '<td align="left">' +
                                                        response.response_data[
                                                            i].code + '</td>';
                                                    content +=
                                                        '<td align="left">' +
                                                        response.response_data[
                                                            i].description +
                                                        '</td>';
                                                    content +=
                                                        '<td align="left">' +
                                                        response.response_data[
                                                            i].line + '</td>';
                                                    content +=
                                                        '<td align="left">' +
                                                        response.response_data[
                                                            i].message +
                                                        '</td>';
                                                    content += '</tr>';
                                                }
                                            });
                                        } else {
                                            content += '<tr>';
                                            content +=
                                                '<td align="center" colspan="4">Sin errores</td>';
                                            content += '</tr>';
                                        }

                                        content += '</tbody>';
                                        content += '</table>';
                                        content += '</div>';

                                        jQuery('#modalMessageImport .modal-body')
                                            .append(content);

                                        /*******************************************************************************************************/

                                        var content_file = '';

                                        content_file += '<hr />';
                                        content_file +=
                                            '<p><strong>Resultado Archivos</strong></p>';
                                        content_file +=
                                        '<div class="table-responsive">';
                                        content_file +=
                                            '<table class="table table-bordered">';
                                        content_file += '<thead>';
                                        content_file += '<tr>';
                                        content_file += '<th align="left">Código</th>';
                                        content_file +=
                                            '<th align="left">Descripción</th>';
                                        content_file +=
                                            '<th align="left">Línea Excel</th>';
                                        content_file += '<th align="left">Mensaje</th>';
                                        content_file += '</tr>';
                                        content_file += '</thead>';
                                        content_file += '<tbody>';

                                        if ($(response.response_data_file).size() > 0) {
                                            $.each(response.response_data_file,
                                                function(i, post) {
                                                    if (response.response_data_file[
                                                            i].code != null ||
                                                        response.response_data_file[
                                                            i].code != undefined) {
                                                        content_file += '<tr>';
                                                        content_file +=
                                                            '<td align="left">' +
                                                            response
                                                            .response_data_file[i]
                                                            .code + '</td>';
                                                        content_file +=
                                                            '<td align="left">' +
                                                            response
                                                            .response_data_file[i]
                                                            .description + '</td>';
                                                        content_file +=
                                                            '<td align="left">' +
                                                            response
                                                            .response_data_file[i]
                                                            .line + '</td>';
                                                        content_file +=
                                                            '<td align="left">' +
                                                            response
                                                            .response_data_file[i]
                                                            .error_archive +
                                                            '</td>';
                                                        content_file += '</tr>';
                                                    }
                                                });
                                        } else {
                                            content_file += '<tr>';
                                            content_file +=
                                                '<td align="center" colspan="4">Sin errores</td>';
                                            content_file += '</tr>';
                                        }

                                        content_file += '</tbody>';
                                        content_file += '</table>';
                                        content_file += '</div>';

                                        jQuery('#modalMessageImport .modal-body')
                                            .append(content_file);

                                        jQuery('#import-file').removeAttr('disabled');
                                    }
                                }
                            }, 1000);
                        },
                        error: function() {
                            $('#wraper_ajax').remove();

                            bootbox.alert({
                                message: "<strong>ERROR: No se ha logrado obtener una respuesta correcta.</strong>",
                                closeButton: false,
                                buttons: {
                                    ok: {
                                        label: 'Aceptar',
                                        className: 'btn-red'
                                    }
                                },
                                callback: function(result) {
                                    $(location).attr('href', "{{route('products.import')}}");
                                }
                            });
                        }
                    });

                };

                function progress_bar_process(percentage, timer, type_send, idIntervalGlobal) {
                    if (type_send == 2) {
                        clearInterval(idIntervalGlobal + 1);
                    }
                    $('.myprogress').attr('aria-valuenow', percentage);
                    $('.myprogress').css('width', percentage + '%');
                    $('.myprogress .sr-only').text(percentage + '% Completado');
                }
            });

        </script>
    </x-slot>
</x-app-layoutt>

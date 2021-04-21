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
            <a href="{{route('categories')}}"><?php echo
                $title; ?></a>
        </li>
        <li class="active">
            <strong>Editar Registro</strong>
        </li>
    </ol>

    <h3><?php echo $title; ?></h3>
    <h3><?php echo $category->title; ?></h3>
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


    <form role="form" id="form1" method="post"
        action="{{route('categories.update')}}"
        enctype="multipart/form-data" class="custom-validate form-groups-bordered">

        @csrf
        <div class="row">
            <div class="col-md-12">

                <ul class="nav nav-tabs left-aligned">
                    <!-- available classes "bordered", "right-aligned" -->
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            <span><i class="fa fa-list-alt" aria-hidden="true"></i></span>
                            <span>Categoría</span>
                        </a>
                    </li>
                    <?php if ($category->parent_id == 0) { ?>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <span><i class="fa fa-money" aria-hidden="true"></i></span>
                            <span>Oferta</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>

                <div class="tab-content tab-validate">
                    <div class="tab-pane active" id="tab1">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Categoría Base</label>
                                        <select class="form-control" name="parent_id" id="parent_id">
                                            <option value="">Seleccionar</option>
                                            <?php echo $categories; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <input type="text" class="form-control required" name="title" id="title"
                                        maxlength="255"
                                        value="{{$category->title}}" />
                                </div>

                                <?php
                                /*?>
                                <div class="form-group">
                                    <label class="control-label">Imagen Home</label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar Imagen</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="main_image">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                                            style="float: none">&times;</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Se recomienda subir imagen de <?php echo
                                        $this->main_image_description; ?> px. En formato JPG.</label>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Tamaño máximo de carga <?php echo
                                        Application\Helper::uploadSizeUser(); ?>.</label>
                                </div>

                                <?php if(!empty($this->category['main_image']) &&
                                file_exists(UPLOAD_URL_ROOT . $this->module . DS . 'img' . DS .
                                $this->category['main_image'])){ ?>
                                <div class="form-group">
                                    <label class="control-label">Imagen Home Actual</label>
                                    <div class="clearfix"></div>
                                    <a href="<?php echo BASE_URL_ROOT . 'upload/' . $this->module . '/img/' . $this->category['main_image']; ?>"
                                        data-fancybox="galeria"><img
                                            src="<?php echo BASE_URL_ROOT . 'upload/' . $this->module . '/img/' . $this->category['main_image']; ?>"
                                            width="170" height="101" /></a>
                                </div>
                                <?php } ?>

                                <div class="form-group">
                                    <label class="control-label">Imagen Banner</label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar Imagen</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="secondary_image">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                                            style="float: none">&times;</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Se recomienda subir imagen de <?php echo
                                        $this->secondary_image_description; ?> px. En formato
                                        JPG.</label>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Tamaño máximo de carga <?php echo
                                        Application\Helper::uploadSizeUser(); ?>.</label>
                                </div>

                                <?php if(!empty($this->category['secondary_image']) &&
                                file_exists(UPLOAD_URL_ROOT . $this->module . DS . 'img' . DS .
                                $this->category['secondary_image'])){ ?>
                                <div class="form-group">
                                    <label class="control-label">Imagen Banner Actual</label>
                                    <div class="clearfix"></div>
                                    <a href="<?php echo BASE_URL_ROOT . 'upload/' . $this->module . '/img/' . $this->category['secondary_image']; ?>"
                                        data-fancybox="galeria"><img
                                            src="<?php echo BASE_URL_ROOT . 'upload/' . $this->module . '/img/' . $this->category['secondary_image']; ?>"
                                            width="170" height="101" /></a>
                                </div>
                                <?php } ?>

                                <div class="form-group">
                                    <label class="control-label">Imagen Icono</label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar Imagen</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="offer_image">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                                            style="float: none">&times;</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Se recomienda subir imagen de <?php echo
                                        $this->offer_image_description; ?> px. En formato JPG.</label>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Tamaño máximo de carga <?php echo
                                        Application\Helper::uploadSizeUser(); ?>.</label>
                                </div>

                                <?php if(!empty($this->category['offer_image']) &&
                                file_exists(UPLOAD_URL_ROOT . $this->module . DS . 'img' . DS .
                                $this->category['offer_image'])){ ?>
                                <div class="form-group">
                                    <label class="control-label">Imagen Icono Actual</label>
                                    <div class="clearfix"></div>
                                    <a href="<?php echo BASE_URL_ROOT . 'upload/' . $this->module . '/img/' . $this->category['offer_image']; ?>"
                                        data-fancybox="galeria"><img
                                            src="<?php echo BASE_URL_ROOT . 'upload/' . $this->module . '/img/' . $this->category['offer_image']; ?>"
                                            width="170" height="101" /></a>
                                </div>
                                <?php } ?>
                                <?php */
                                ?>

                                <div class="form-group main-group-category"
                                    style="display:<?php echo $category->parent_id == 0 ? 'block' : 'none'; ?>">
                                    <label class="control-label">¿Contenido Destacado?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="featured" id="featured" value="1" <?php if ($category['featured'] == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Activar Registro?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="active" id="active" value="1" <?php if ($category['active'] == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <?php if ($category->parent_id == 0) { ?>
                    <div class="tab-pane" id="tab2">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">Descuento %</label>
                                    <input type="text" class="form-control" name="discount" id="discount"
                                        value="<?php echo $category['discount']; ?>" />
                                    <label class="formNote">* Para indicar decimales, estos deben ser expresados con un
                                        punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descuento Desde</label>
                                    <input type="text" class="form-control datepicker-start-daysBeforeDisabled"
                                        name="start_date" id="start_date"
                                        value="<?php echo $category['start_date']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descuento Hasta</label>
                                    <input type="text" class="form-control datepicker-end-daysBeforeDisabled"
                                        name="end_date" id="end_date"
                                        value="<?php echo $category['end_date']; ?>" />
                                </div>

                                <div class="form-group">
                                    <select multiple="multiple" size="10" name="relations[]"
                                        class="dual-list-box"></select>
                                </div>

                            </div>

                        </div>

                    </div>
                    <?php } ?>

                </div>
                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('categories')}}"
                        type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                    <input type="hidden" name="id"
                        value="<?php echo $category['id']; ?>" />
                </div>

            </div>
        </div>

    </form>

    <br />
    <x-slot name="js">
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $.validator.addMethod("validateRolUnicoTributario", function(value, element) {
                    var label = element.id;

                    return this.optional(element) || validaRut(value, label);
                }, "El RUT ingresado es inválido");

                $.validator.addClassRules({
                    rut: {
                        validateRolUnicoTributario: true
                    }
                });

                $('#form1').validate({
                    errorElement: 'span',
                    errorClass: 'validate-has-error',
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('validate-has-error');
                        $(element).addClass('error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('validate-has-error');
                        $(element).removeClass('error');
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
                    },
                    ignore: [],
                    invalidHandler: function() {
                        setTimeout(function() {
                            $('.nav-tabs a small.required').remove();
                            var validatePane = $(
                                '.tab-content.tab-validate .tab-pane:has(input.error), .tab-content.tab-validate .tab-pane:has(select.error)'
                                ).each(function() {
                                var id = $(this).attr('id');
                                $('.nav-tabs').find('a[href^="#' + id + '"]')
                                    .append(' <small class="required">***</small>');
                                console.log(id);

                                $('.nav-tabs li').removeClass('active');
                                $('.tab-content div').removeClass('active');

                                $('.nav-tabs').find('a[href^="#' + id + '"]')
                                    .parent().addClass('active');
                                $('.tab-content div#' + id + '').addClass('active');
                            });
                        });
                    },
                });

                $.fn.loadDataProductsRelations = function() {

                    $('.dual-list-box').bootstrapDualListbox('destroy');
                    $('.dual-list-box').empty();
                    var contenedor = $('.dual-list-box').parent();
                    $('.dual-list-box').remove();
                    contenedor.append(
                        '<select multiple="multiple" size="10" name="relations[]" class="dual-list-box"></select>'
                        );

                    var url = "{{route('categories.getProductsRelations')}}";
                    var data = {
                        id: $('#form1 input[name="id"]').val()
                    }

                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        type: "POST",
                        encoding: "UTF-8",
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function(response) {

                            $('select.dual-list-box').append(response.relations_selected);

                            var dual_list_box2 = $('.dual-list-box').bootstrapDualListbox({
                                nonSelectedListLabel: 'No Seleccionados',
                                selectedListLabel: 'Seleccionados',
                                moveOnSelect: false,
                                nonSelectedFilter: '',
                                filterPlaceHolder: 'Filtrar',
                                moveSelectedLabel: 'Mover Seleccionados',
                                moveAllLabel: 'Mover Todos',
                                removeSelectedLabel: 'Remover Seleccionados',
                                removeAllLabel: 'Remover Todos',
                                infoText: 'Visualizando {0} registros',
                                infoTextFiltered: 'Filtrando {0} de {1}',
                                infoTextEmpty: 'Lista vacia'
                            });
                        }
                    });
                };

                $('select[name=parent_id]').change(function() {
                    if ($(this).val() == "") {
                        $('.main-group-category').css('display', 'block');
                    } else {
                        $('.main-group-category').css('display', 'none');
                    }

                });

                $('body').loadDataProductsRelations();
            });

        </script>
    </x-slot>
</x-app-layoutt>

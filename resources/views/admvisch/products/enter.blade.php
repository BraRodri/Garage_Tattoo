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
            <a href="<?php echo BASE_URL . $module; ?>"><?php echo
                                                        $title; ?></a>
        </li>
        <li class="active">
            <strong>Nuevo Ingreso</strong>
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

    <form role="form" id="form1" method="post" action="{{route('products.insert')}}" enctype="multipart/form-data" class="custom-validate form-groups-bordered">

        @csrf
        <div class="row">
            <div class="col-md-12">

                <ul class="nav nav-tabs left-aligned">
                    <!-- available classes "bordered", "right-aligned" -->
                    <li class="active"><a href="#tab1" data-toggle="tab">
                            <span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                            <span>Producto</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <span><i class="fa fa-connectdevelop" aria-hidden="true"></i></span>
                            <span>Relacionados</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab3" data-toggle="tab">
                            <span><i class="fa fa-list-alt" aria-hidden="true"></i></span>
                            <span>Descripciones</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab4" data-toggle="tab">
                            <span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                            <span>Atributos</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab5" data-toggle="tab">
                            <span><i class="fa fa-google" aria-hidden="true"></i></span>
                            <span>Configuración SEO</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content tab-validate">
                    <div class="tab-pane active" id="tab1">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Tipo Producto</label>
                                        <select class="form-control required" name="types_id" id="types_id">
                                            <option value="">Seleccionar</option>
                                            <?php if (current($types) > 0) {
                                                foreach (end($types) as $type) { ?>
                                                    <option value="<?php echo $type['id']; ?>">
                                                        <?php echo $type['title']; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Categoría</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <select class="searchable-optionList required" name="categories[]" id="categories" multiple="multiple" style="min-width:300px">
                                                    <?php echo $categories; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php /*
                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Marca</label>
                                        <select class="form-control required" name="brands_id" id="brands_id">
                                            <option value="">Seleccionar</option>
                                            <?php if (current($brands) > 0) {
                                            foreach (end($brands) as $brand) { ?>
                                            <option value="<?php echo $brand['id']; ?>">
                                                <?php echo $brand['title']; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Modelo</label>
                                    <input type="text" class="form-control" name="model" id="model" maxlength="255" />
                                </div>
                                */ ?>

                                <div class="form-group">
                                    <label class="control-label">Código Interno</label>
                                    <input type="text" class="form-control required" name="sku" id="sku" maxlength="255" />
                                    <label class="formNote">* Código debe ser único para cada producto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <input type="text" class="form-control required" name="title" id="title" maxlength="255" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Precio Normal</label>
                                    <input type="text" class="form-control required digits" name="normal_price" id="normal_price" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Precio Oferta</label>
                                    <input type="text" class="form-control required digits" name="offer_price" id="offer_price" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Stock</label>
                                    <input type="text" class="form-control required digits" name="stock" id="stock" />
                                    <label class="formNote">* En caso de no controlar descuento de stock, completar con
                                        0.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Control Stock?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="stock_control" id="stock_control" value="1" checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Mínimo Cantidad</label>
                                    <input type="text" class="form-control required digits" name="minimum_amount" id="minimum_amount" value="0" />
                                    <label class="formNote">* Cantidad mínima de compra, en caso de no existir un mínimo
                                        completar con 0.</label>
                                </div>

                                <!--
                                <div class="form-group">
                                    <label class="control-label">Descuento %</label>
                                    <input type="text" class="form-control required digits" name="discount"
                                        id="discount" value="0" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descuento Desde</label>
                                    <input type="text" class="form-control datepicker-start-daysBeforeDisabled"
                                        name="start_date" id="start_date" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descuento Hasta</label>
                                    <input type="text" class="form-control datepicker-end-daysBeforeDisabled"
                                        name="end_date" id="end_date" />
                                </div>
                                -->
                                <div class="form-group">
                                    <label class="control-label">Peso (kg)</label>
                                    <input type="text" class="form-control" name="weight" id="weight" value="0" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Largo (cm)</label>
                                    <input type="text" class="form-control" name="lenght" id="lenght" value="0" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Ancho (cm)</label>
                                    <input type="text" class="form-control" name="width" id="width" value="0" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Alto (cm)</label>
                                    <input type="text" class="form-control" name="height" id="height" value="0" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <?php /*
                                <div class="form-group">
                                    <label class="control-label">Unidad de Medida</label>
                                    <input type="text" class="form-control" name="medida" id="height"  />
                                </div>
                                */ ?>

                                <div class="form-group">
                                    <label class="control-label">Ficha Técnica</label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar Archivo</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="archive">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">
                                        * Formato <?php echo $archive_description; ?>.<br>
                                        * Tamaño máximo de carga <?php echo
                                                                    Application\Helper::uploadSizeUser(); ?>.
                                    </label>

                                </div>

                                <?php /*
                                <div class="form-group">
                                    <label class="control-label">Certificado</label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar Archivo</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="certificado">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                                            style="float: none">&times;</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">
                                        * Formato <?php echo $archive_description; ?>.<br>
                                        * Tamaño máximo de carga <?php echo
                                        Application\Helper::uploadSizeUser(); ?>.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Código ChileCompra</label>
                                    <input type="text" class="form-control" name="chilecompracode" id="chilecompracode"
                                        maxlength="100" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Contenido Nuevo?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="new" id="new" value="1" checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿A pedido?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="a_pedido" id="a_pedido" value="1" checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                */ ?>

                                <div class="form-group">
                                    <label class="control-label">¿Contenido Destacado?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="featured" id="featured" value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Contenido Oferta?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="offer" id="offer" value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Despacho Domicilio?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="shipping_active" id="shipping_active" value="1" checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Retiro en Tienda?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="office_shipping_active" id="office_shipping_active" value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Despacho Gratis?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="shipping_free" id="shipping_free" value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
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

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="tab2">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <select multiple="multiple" size="10" name="relations[]" class="dual-list-box"></select>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="tab3">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">Descripción General</label>
                                    <textarea class="form-control ckeditor" name="general_description" id="general_description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción Técnica</label>
                                    <textarea class="form-control ckeditor" name="technical_description" id="technical_description"></textarea>
                                </div>

                                <?php /*
                                <div class="form-group">
                                    <label class="control-label">Descripción Despachos</label>
                                    <textarea class="form-control ckeditor" name="shipping_description"
                                        id="shipping_description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción Garantia</label>
                                    <textarea class="form-control ckeditor" name="guarantee_description"
                                        id="guarantee_description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Video</label>
                                    <textarea class="form-control ckeditor" name="video_description"
                                        id="video_description"></textarea>
                                </div>
                                */ ?>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="tab4">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">¿Activar Atributos?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="attribute_active" id="attribute_active" value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Seleccione el tipo de atributo:</label>
                                    <select class="form-control w-75" name="attributo" id="losAttributos">
                                        <option value="">Seleccionar</option>
                                        <?php if (current($attributes) > 0) {
                                            foreach (end($attributes) as $atribute) { ?>
                                                <option value="<?php echo $atribute['id'].'-'.$atribute['title']; ?>">
                                                    <?php echo $atribute['title']; ?></option>
                                        <?php }
                                        } ?>
                                    </select>


                                    <div class="clearfix"></div>
                                    <hr>
                                    <div id="combinaciones">
                                        
                                        <div id="attributoEspec">
                                        </div>
                                        <hr>
                                        
                                        <div id="attributosSelect">
                                
                                        
                                        </div>
                                    </div>
                                    <div id="listaCom">
                                    </div>
                                </div>

                                <div id="divDinamico">
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="tab5">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">Título</label>
                                    <input type="text" class="form-control" name="meta_title" id="meta_title" maxlength="255" />
                                    <label class="formNote">
                                        * Titulo de la página: es el título que deseamos que aparezca en el buscador.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Autor</label>
                                    <input type="text" class="form-control" name="meta_author" id="meta_author" maxlength="100" />
                                    <label class="formNote">
                                        * Autor: El nombre del webmaster o de la empresa autora de la página web.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <input type="text" class="form-control" name="meta_description" id="meta_description" />
                                    <label class="formNote">
                                        * Explica con un par de frases el contenido de la web.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Palabras Claves (Keywords)</label>
                                    <textarea class="form-control" name="meta_keyword" id="meta_keyword" rows="5"></textarea>
                                    <label class="formNote">
                                        * Palabras que expecifiquen el contenido de la web. Por ejemplo: juegos, juegos
                                        online, etc. (max: 200 caracteres).
                                    </label>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Robots</label>
                                        <select class="form-control" name="meta_robots" id="meta_robots">
                                            <option value="">Seleccionar</option>
                                            <option value="index,follow">Indexar la página y seguir los enlaces</option>
                                            <option value="index,nofollow">Indexar la página y no seguir los enlaces
                                            </option>
                                            <option value="noindex,follow">No indexar la página y seguir los enlaces
                                            </option>
                                            <option value="noindex,nofollow">No indexar la página y no seguir los
                                                enlaces</option>
                                        </select>
                                        <div class="clearfix"></div>
                                        <label class="formNote">
                                            * Acción del motor - Especifica la acción del motor de búsqueda.
                                        </label>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('products')}}" type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                </div>

            </div>
        </div>

    </form>

    <br />
    <x-slot name="js">
        <script src="{{asset('assets/js/util/attributes.js')}}" type="text/javascript"></script>
        <script type="text/javascript">
            function aggAtributoProduct() {
                var id_atributo = document.getElementById("losAttributos").value;

                if (id_atributo) {
                    var url = "{{route('products.attribute.get', ':id')}}";
                    url = url.replace(':id', id_atributo);
                    var data = {}

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        encoding: "UTF-8",
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function(response) {

                            console.log(response.data);

                            var atributosValues = response.data.values;
                            var atributeNombre = response.data.title;
                            var atribute_id = response.data.id;

                            var valoresSeparados = atributosValues.split(',');

                            var htmlDivContainer = '<div class="form-group">';
                            htmlDivContainer += '<label class="control-label">' + atributeNombre + '</label>';
                            htmlDivContainer += '<select class="form-control js-example-basic-multiple" multiple="multiple" id="" name="" placeholder="Seleccione...">';
                            htmlDivContainer += '<option>1</option>';
                            htmlDivContainer += '</select>';
                            htmlDivContainer += '<div class="clearfix"></div>';
                            htmlDivContainer += '</div>';

                            $("#divDinamico").append(htmlDivContainer);
                        }
                    });

                } else {
                    alert("Por favor, seleccione un atributo para agregar.");
                }

            }

            jQuery(document).ready(function($) {

                $('.js-example-basic-multiple').select2();

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
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('validate-has-error');
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

                    var url = "{{route('products.getProductsRelations')}}";
                    var data = {}

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
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

                $('body').loadDataProductsRelations();

                $('.searchable-optionList').searchableOptionList({
                    maxHeight: '300px',
                    showSelectAll: true,
                    texts: {
                        noItemsAvailable: 'Resultado no encontrado',
                        selectAll: 'Seleccionar Todo',
                        selectNone: 'Remover Selección',
                        quickDelete: '&times;',
                        searchplaceholder: 'Haz click para buscar',
                        loadingData: 'Cargando...',
                        itemsSelected: '{$a} items seleccionados'
                    },
                });
            });
        </script>
    </x-slot>
</x-app-layoutt>
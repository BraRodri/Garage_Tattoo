<x-app-layoutt>

    @section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

    <ol class="breadcrumb bc-2" >
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <?php echo $parent_title; ?>
        </li>
        <li>
            <a href="<?php echo BASE_URL . $module; ?>"><?php echo $title; ?></a>
        </li>
        <li class="active">
            <strong>Editar Registro</strong>
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


    <form role="form" id="form1" method="post" action="{{route('products.update')}}" enctype="multipart/form-data" class="custom-validate form-groups-bordered">

        @csrf
        <div class="row">
            <div class="col-md-12">

                <ul class="nav nav-tabs left-aligned"><!-- available classes "bordered", "right-aligned" -->
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
                            <span><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                            <span>Galería</span>
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
                                        <label class="control-label">Tipo Venta</label>
                                        <select class="form-control required" name="types_id" id="types_id">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            if(current($types) > 0) {
                                                foreach (end($types) AS $type) {
                                                    $selected = (isset($product['types_id']) && !empty($product['types_id']) && $product['types_id'] == $type['id'])? 'selected="selected"' : '';
                                                    ?>
                                                    <option value="<?php echo $type['id']; ?>" <?php echo $selected; ?>><?php echo $type['title']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
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

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Marca</label>
                                        <select class="form-control required" name="brands_id" id="brands_id">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            if(current($brands) > 0) {
                                                foreach (end($brands) AS $brand) {
                                                    $selected = (isset($product['brands_id']) && !empty($product['brands_id']) && $product['brands_id'] == $brand['id'])? 'selected="selected"' : '';
                                                    ?>
                                                    <option value="<?php echo $brand['id']; ?>" <?php echo $selected; ?>><?php echo $brand['title']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Modelo</label>
                                    <input type="text" class="form-control" name="model" id="model" maxlength="255" value="<?php echo $product['model']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Código Interno</label>
                                    <input type="text" class="form-control required" name="sku" id="sku" maxlength="255" value="<?php echo $product['sku']; ?>" />
                                    <label class="formNote">* Código debe ser único para cada producto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <input type="text" class="form-control required" name="title" id="title" maxlength="255" value="<?php echo $product['title']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Precio Normal</label>
                                    <input type="text" class="form-control required digits" name="normal_price" id="normal_price" value="<?php echo $product['normal_price']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Precio Oferta</label>
                                    <input type="text" class="form-control required digits" name="offer_price" id="offer_price" value="<?php echo $product['offer_price']; ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Stock</label>
                                    <input type="text" class="form-control required digits" name="stock" id="stock" value="<?php echo $product['stock']; ?>" />
                                    <label class="formNote">* En caso de no controlar descuento de stock, completar con 0.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Control Stock?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="stock_control" id="stock_control" value="1" <?php if($product['stock_control'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Mínimo Cantidad</label>
                                    <input type="text" class="form-control required digits" name="minimum_amount" id="minimum_amount" value="<?php echo $product['minimum_amount']; ?>" />
                                    <label class="formNote">* Cantidad mínima de compra, en caso de no existir un mínimo completar con 0.</label>
                                </div>

                                <!--
                                <div class="form-group">
                                    <label class="control-label">Descuento %</label>
                                    <input type="text" class="form-control required digits" name="discount" id="discount" value="<?php echo $product['discount']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descuento Desde</label>
                                    <input type="text" class="form-control datepicker-start-daysBeforeDisabled" name="start_date" id="start_date" value="<?php echo $product['start_date']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descuento Hasta</label>
                                    <input type="text" class="form-control datepicker-end-daysBeforeDisabled" name="end_date" id="end_date" value="<?php echo $product['end_date']; ?>" />
                                </div>
                            -->

                                <div class="form-group">
                                    <label class="control-label">Peso (kg)</label>
                                    <input type="text" class="form-control" name="weight" id="weight" value="<?php echo $product['weight']; ?>" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Largo (cm)</label>
                                    <input type="text" class="form-control" name="lenght" id="lenght" value="<?php echo $product['lenght']; ?>" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Ancho (cm)</label>
                                    <input type="text" class="form-control" name="width" id="width" value="<?php echo $product['width']; ?>" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Alto (cm)</label>
                                    <input type="text" class="form-control" name="height" id="height" value="<?php echo $product['height']; ?>" />
                                    <label class="formNote">* Decimales expresados con un punto.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Unidad de Medida</label>
                                    <input type="text" class="form-control" name="medida" id="height" value="<?php echo $product['medida']; ?>" />

                                </div>

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
                                        * Tamaño máximo de carga <?php echo Application\Helper::uploadSizeUser(); ?>.
                                    </label>

                                </div>


                                <?php if(!empty($product['archive']) && file_exists(UPLOAD_URL_ROOT . $module . DS . 'pdf' . DS . $product['archive'])){ ?>
                                    <div class="form-group">
                                        <label class="control-label">Ficha Técnica Actual</label>
                                        <div class="clearfix"></div>
                                        <a href="<?php echo BASE_URL_ROOT . 'files/' . $module . '/pdf/' . $product['archive']; ?>" target="_blank">Ver Archivo PDF</a>
                                    </div>
                                <?php } ?>

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
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">
                                        * Formato <?php echo $archive_description; ?>.<br>
                                        * Tamaño máximo de carga <?php echo Application\Helper::uploadSizeUser(); ?>.
                                    </label>

                                </div>


                                <?php if(!empty($product['certificado']) && file_exists(UPLOAD_URL_ROOT . $module . DS . 'pdf' . DS . $product['certificado'])){ ?>
                                    <div class="form-group">
                                        <label class="control-label">Certificado Actual</label>
                                        <div class="clearfix"></div>
                                        <a href="<?php echo BASE_URL_ROOT . 'files/' . $module . '/pdf/' . $product['certificado']; ?>" target="_blank">Ver Archivo PDF</a>
                                    </div>
                                <?php } ?>

                                <div class="form-group">
                                    <label class="control-label">Colores</label>
                                    <input type="text" class="form-control tagsinput" name="color" id="tags" value="<?php echo $product['color']; ?>" />
                                    <label class="formNote">* Para agregar múltiples colores, presionar enter al finalizar de escribir un color.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Tallas</label>
                                    <input type="text" class="form-control tagsinput" name="talla" id="tags" value="<?php echo $product['talla']; ?>" />
                                    <label class="formNote">* Para agregar múltiples tallas, presionar enter al finalizar de escribir una talla.</label>
                                </div>

                                <?php /*?>
                                <div class="form-group">
                                    <label class="control-label">Código ChileCompra</label>
                                    <input type="text" class="form-control" name="chilecompracode" id="chilecompracode" maxlength="100" value="<?php echo $this->product['chilecompracode']; ?>" />
                                </div>
                                <?php */?>

                                <div class="form-group">
                                    <label class="control-label">¿Contenido Nuevo?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="new" id="new" value="1" <?php if($product['new'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿A pedido?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="a_pedido" id="a_pedido" value="1" <?php if($product['a_pedido'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Contenido Destacado?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="featured" id="featured" value="1" <?php if($product['featured'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Contenido Oferta?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="offer" id="offer" value="1" <?php if($product['offer'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Despacho Domicilio?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="shipping_active" id="shipping_active" value="1" <?php if($product['shipping_active'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Retiro en Tienda?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="office_shipping_active" id="office_shipping_active" value="1" <?php if($product['office_shipping_active'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Despacho Gratis?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="shipping_free" id="shipping_free" value="1" <?php if($product['shipping_free'] == 1){ echo "checked='checked'"; } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label">¿Activar Registro?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="active" id="active" value="1" <?php if($product['active'] == 1){ echo "checked='checked'"; } ?>>
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
                                    <textarea class="form-control ckeditor" name="general_description" id="general_description"><?php echo $product['general_description']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción Técnica</label>
                                    <textarea class="form-control ckeditor" name="technical_description" id="technical_description"><?php echo $product['technical_description']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción Despachos</label>
                                    <textarea class="form-control ckeditor" name="shipping_description" id="shipping_description"><?php echo $product['shipping_description']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción Garantia</label>
                                    <textarea class="form-control ckeditor" name="guarantee_description" id="guarantee_description"><?php echo $product['guarantee_description']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Video</label>
                                    <textarea class="form-control ckeditor" name="video_description" id="video_description"><?php echo $product['video_description']; ?></textarea>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="tab4">


                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <button type="button" class="btn btn-orange" id="show-form-gallery"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>
                                </div>

                            </div>

                        </div>


                        <table class="table table-bordered responsive" id="table-galleries">
                            <thead>
                            <tr>
                                <th>Imagen</th>
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


                    <div class="tab-pane" id="tab5">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">Título</label>
                                    <input type="text" class="form-control" name="meta_title" id="meta_title" maxlength="255" value="<?php echo $product['meta_title']; ?>" />
                                    <label class="formNote">
                                        * Titulo de la página: es el título que deseamos que aparezca en el buscador.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Autor</label>
                                    <input type="text" class="form-control" name="meta_author" id="meta_author" maxlength="100" value="<?php echo $product['meta_author']; ?>" />
                                    <label class="formNote">
                                        * Autor: El nombre del webmaster o de la empresa autora de la página web.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <input type="text" class="form-control" name="meta_description" id="meta_description" value="<?php echo $product['meta_description']; ?>" />
                                    <label class="formNote">
                                        * Explica con un par de frases el contenido de la web.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Palabras Claves (Keywords)</label>
                                    <textarea class="form-control" name="meta_keyword" id="meta_keyword" rows="5"><?php echo $product['meta_keyword']; ?></textarea>
                                    <label class="formNote">
                                        * Palabras que expecifiquen el contenido de la web. Por ejemplo: juegos, juegos online, etc. (max: 200 caracteres).
                                    </label>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Robots</label>
                                        <select class="form-control" name="meta_robots" id="meta_robots">
                                            <option value="">Seleccionar</option>
                                            <option value="index,follow" <?php echo (isset($product['meta_robots']) && !empty($product['meta_robots']) && $product['meta_robots'] == 'index,follow')? 'selected="selected"' : ''; ?>>Indexar la página y seguir los enlaces</option>
                                            <option value="index,nofollow" <?php echo (isset($product['meta_robots']) && !empty($product['meta_robots']) && $product['meta_robots'] == 'index,nofollow')? 'selected="selected"' : ''; ?>>Indexar la página y no seguir los enlaces</option>
                                            <option value="noindex,follow" <?php echo (isset($product['meta_robots']) && !empty($product['meta_robots']) && $product['meta_robots'] == 'noindex,follow')? 'selected="selected"' : ''; ?>>No indexar la página y seguir los enlaces</option>
                                            <option value="noindex,nofollow" <?php echo (isset($product['meta_robots']) && !empty($product['meta_robots']) && $product['meta_robots'] == 'noindex,nofollow')? 'selected="selected"' : ''; ?>>No indexar la página y no seguir los enlaces</option>
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
                    <a href="{{route('products')}}" type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y Volver</a>
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>" />
                </div>

            </div>
        </div>

    </form>

    <br />

    <div class="modal fade" id="modal-add-gallery">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Ingreso de Galería</h4>
                </div>

                <form role="form" id="form-gallery-add" method="post" class="custom-validate" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Imagen <span class="formNoteFeatured">(1)</span></label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-bottom:0">
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar Imagen</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="image">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">¿Activar?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="active" id="active" value="1" checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="formNoteFeatured">
                                    (1) * Se recomienda subir imagen de <?php echo $image_description; ?> px. En formato JPG.<br>
                                    * Tamaño máximo de carga <?php echo Application\Helper::uploadSizeUser(); ?>.
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-info">Guardar Imagen</button>
                        <input type="hidden" name="products_id" id="products_id" value="<?php echo $product['id']; ?>" />
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

    <div class="modal fade" id="modal-edit-gallery">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Actualización de Galería</h4>
                </div>

                <form role="form" id="form-gallery-edit" method="post" class="custom-validate" enctype="multipart/form-data">

                    @csrf
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Imagen <span class="formNoteFeatured">(1)</span></label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-bottom:0">
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new">Seleccionar Imagen</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="image">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Imagen Actual</label>
                                    <div class="clearfix"></div>
                                    <a href="#" data-fancybox="galeria" class="gallery-product"><img src="#" width="170" height="101" /></a>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">¿Activar?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                                            <input type="checkbox" name="active" id="active" value="1" checked="checked">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="formNoteFeatured">
                                    (1) * Se recomienda subir imagen de <?php echo $image_description; ?> px. En formato JPG.<br>
                                    * Tamaño máximo de carga <?php echo Application\Helper::uploadSizeUser(); ?>.
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-info">Guardar Imagen</button>
                        <input type="hidden" name="products_id" id="products_id" value="<?php echo $product['id']; ?>" />
                        <input type="hidden" name="id" id="id" value="" />
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
    <x-slot name="js">
        <script type="text/javascript">

jQuery( document ).ready( function( $ ) {

$.validator.addMethod("validateRolUnicoTributario", function(value, element) {
    var label = element.id;

    return this.optional(element) || validaRut(value, label);
}, "El RUT ingresado es inválido");

$.validator.addClassRules({
    rut : { validateRolUnicoTributario : true }
});

$('#form1').validate({
    errorElement: 'span',
    errorClass: 'validate-has-error',
    highlight: function (element) {
        $(element).closest('.form-group').addClass('validate-has-error');
        $(element).addClass('error');
    },
    unhighlight: function (element) {
        $(element).closest('.form-group').removeClass('validate-has-error');
        $(element).removeClass('error');
    },
    errorPlacement: function (error, element)
    {
        if(element.closest('.has-switch').length)
        {
            error.insertAfter(element.closest('.has-switch'));
        }
        else
        if(element.parent('.checkbox, .radio').length || element.parent('.input-group').length)
        {
            error.insertAfter(element.parent());
        }
        else
        {
            error.insertAfter(element);
        }
    },
    ignore: [],
    invalidHandler: function() {
        setTimeout(function() {
            $('.nav-tabs a small.required').remove();
            var validatePane = $('.tab-content.tab-validate .tab-pane:has(input.error), .tab-content.tab-validate .tab-pane:has(select.error)').each(function() {
                var id = $(this).attr('id');
                $('.nav-tabs').find('a[href^="#' + id + '"]').append(' <small class="required">***</small>');
                console.log(id);

                $('.nav-tabs li').removeClass('active');
                $('.tab-content div').removeClass('active');

                $('.nav-tabs').find('a[href^="#' + id + '"]').parent().addClass('active');
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
    contenedor.append('<select multiple="multiple" size="10" name="relations[]" class="dual-list-box"></select>');

    var url ="{{route('products.getProductsRelations')}}";
    var data = {
        id: $('#form1 input[name="id"]').val()
    }

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

        type: "POST",
        encoding:"UTF-8",
        url: url,
        data: data,
        dataType:'json',
        success: function(response){

            $('select.dual-list-box').append(response.relations_selected);

            var dual_list_box2 = $('.dual-list-box').bootstrapDualListbox({
                nonSelectedListLabel: 'No Seleccionados',
                selectedListLabel: 'Seleccionados',
                moveOnSelect: false,
                nonSelectedFilter: '',
                filterPlaceHolder: 'Filtrar',
                moveSelectedLabel: 'Mover Seleccionados',
                moveAllLabel: 'Mover Todos',
                removeSelectedLabel	: 'Remover Seleccionados',
                removeAllLabel: 'Remover Todos',
                infoText: 'Visualizando {0} registros',
                infoTextFiltered: 'Filtrando {0} de {1}',
                infoTextEmpty: 'Lista vacia'
            });
        }
    });
};

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// MODULOS GALERIAS DE PRODUCTO
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$.fn.loadDataTableProductsGalleries = function() {

    var url = "{{route('productsGalleries')}}";
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
            $('#wraper_ajax').remove();
            $('#table-galleries tbody').html(response.data);
        }
    });
};

$('#form-gallery-add').validate({
    errorElement: 'span',
    errorClass: 'validate-has-error',
    highlight: function(element){
        $(element).closest('.form-group').addClass('validate-has-error');
    },
    unhighlight: function(element){
        $(element).closest('.form-group').removeClass('validate-has-error');
    },
    errorPlacement: function (error, element)
    {
        if(element.closest('.has-switch').length)
        {
            error.insertAfter(element.closest('.has-switch'));
        }
        else if(element.parent('.checkbox, .radio').length || element.parent('.input-group').length)
        {
            error.insertAfter(element.parent());
        }
        else
        {
            error.insertAfter(element);
        }
    },
    submitHandler: function(ev){

        var $thisForm = $('#form-gallery-add');
        var $thisModal = $('#modal-add-gallery');

        var url = "{{route('productsGalleries.insert')}}";
        var active = 0;

        if($thisForm.find('input[name="active"]:checked').val() == 1){
            active = 1;
        }

        var formData = new FormData();
        formData.append('image', $thisForm.find('input[type="file"]')[0].files[0]);
        formData.append("active", active);
        formData.append("products_id", $thisForm.find('input[name="products_id"]').val());

        $thisModal.find('.modal-body .alert-danger, .modal-body .alert-success').remove();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: "POST",
            encoding:"UTF-8",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            dataType:'json',

            error: function() {
                $('#wraper_ajax').remove();
                var message = '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                $thisModal.find('.modal-body').prepend(message);
            },
            success: function(response) {
                $('#wraper_ajax').remove();
                if(response.error == 0){
                    if(response.type == 'upload'){
                        var message = '<div class="alert alert-danger"><strong>ERROR!</strong> El archivo no se pudo cargar. Asegúrese de que su archivo no supere el tamaño indicado o no cumpla con el formato establecido.</div>';
                        $thisModal.find('.modal-body').prepend(message);
                    }
                    else if(response.type == 'failure'){
                        var message = '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                        $thisModal.find('.modal-body').prepend(message);
                    } else {
                        $thisForm[0].reset();

                        var message = '<div class="alert alert-success"><strong>OK!</strong> ingreso realizado correctamente.</div>';
                        $thisModal.find('.modal-body').prepend(message);

                        setTimeout(function() {
                            $thisModal.find('.modal-body .alert-danger, .modal-body .alert-success').remove();
                            $thisModal.modal('hide');
                        }, 1000);

                        $('body').loadDataTableProductsGalleries();
                    }
                }
            }
        });
    }
});

$('#form-gallery-edit').validate({
    errorElement: 'span',
    errorClass: 'validate-has-error',
    highlight: function(element){
        $(element).closest('.form-group').addClass('validate-has-error');
    },
    unhighlight: function(element){
        $(element).closest('.form-group').removeClass('validate-has-error');
    },
    errorPlacement: function (error, element)
    {
        if(element.closest('.has-switch').length)
        {
            error.insertAfter(element.closest('.has-switch'));
        }
        else if(element.parent('.checkbox, .radio').length || element.parent('.input-group').length)
        {
            error.insertAfter(element.parent());
        }
        else
        {
            error.insertAfter(element);
        }
    },
    submitHandler: function(ev){

        var $thisForm = $('#form-gallery-edit');
        var $thisModal = $('#modal-edit-gallery');

        var url = "{{route('productsGalleries.update')}}";
        var active = 0;

        if($thisForm.find('input[name="active"]:checked').val() == 1){
            active = 1;
        }

        var formData = new FormData();
        formData.append('image', $thisForm.find('input[type="file"]')[0].files[0]);
        formData.append("active", active);
        formData.append("products_id", $thisForm.find('input[name="products_id"]').val());
        formData.append("id", $thisForm.find('input[name="id"]').val());

        $thisModal.find('.modal-body .alert-danger, .modal-body .alert-success').remove();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: "POST",
            encoding:"UTF-8",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            dataType:'json',

            error: function() {
                $('#wraper_ajax').remove();
                var message = '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                $thisModal.find('.modal-body').prepend(message);
            },
            success: function(response) {
                $('#wraper_ajax').remove();
                if(response.error == 0){
                    if(response.type == 'upload'){
                        var message = '<div class="alert alert-danger"><strong>ERROR!</strong> El archivo no se pudo cargar. Asegúrese de que su archivo no supere el tamaño indicado o no cumpla con el formato establecido.</div>';
                        $thisModal.find('.modal-body').prepend(message);
                    }
                    else if(response.type == 'failure'){
                        var message = '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                        $thisModal.find('.modal-body').prepend(message);
                    } else {
                        $thisForm[0].reset();

                        var message = '<div class="alert alert-success"><strong>OK!</strong> actualización realizada correctamente.</div>';
                        $thisModal.find('.modal-body').prepend(message);

                        setTimeout(function() {
                            $thisModal.find('.modal-body .alert-danger, .modal-body .alert-success').remove();
                            $thisModal.modal('hide');
                        }, 1000);

                        $('body').loadDataTableProductsGalleries();
                    }
                }
            }
        });
    }
});

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$.fn.changeStatus = function(thisElement, module) {

    var $element = thisElement;
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
        success: function (response) {
            if (response.status == 1) {
                $element.find('span').removeAttr('class').attr('class', '');
                $element.find('span').addClass('badge');
                $element.find('span').addClass(response.class_status);
                $element.find('span').text(response.text_status);
            }
        }
    });
};

$.fn.deleteRegister = function(thisElement, module, numberTab, nameTable){

    var $element = thisElement;
    var id = $element.attr('id');

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

                var url = '<?php echo BASE_URL?>'+'productsGalleries/delete/' + id;

                $('#tab' + numberTab + ' .alert-danger, #tab' + numberTab + ' .alert-success').remove();

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: "GET",
                    encoding:"UTF-8",
                    url: url,
                    dataType:'json',

                    error: function() {
                        $('#wraper_ajax').remove();
                        var message = '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                        $(message).insertBefore('#tab' + numberTab + ' #table-' + nameTable);
                    },
                    success: function(response) {
                        $('#wraper_ajax').remove();
                        if(response.type == 'success') {
                            var message = '<div class="alert alert-success"><strong>OK!</strong> registro eliminado correctamente.</div>';
                        } else {
                            var message = '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                        }
                        $(message).insertBefore('#tab' + numberTab + ' #table-' + nameTable);

                        if(module == 'productsCombinations'){
                            $('body').loadDataTableProductsCombinations();
                        }
                        else if(module == 'productsGalleries'){
                            $('body').loadDataTableProductsGalleries();
                        } else {

                        }
                    }
                });
            }
        }
    });
};

$('body').loadDataProductsRelations();
$('body').loadDataTableProductsGalleries();

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

jQuery(document).on('click', '#show-form-gallery', function(e) {
e.preventDefault();
$('#modal-add-gallery').modal('show', {backdrop: 'static'});
});

//------------------------------------------------------------------------------------

jQuery(document).on('click', '.show-form-edit-gallery', function(e) {
e.preventDefault();
$('#modal-edit-gallery').modal('show', {backdrop: 'static'});

var id = $(this).attr('id');
var url = '<?php echo BASE_URL?>'+'productsGalleries/edit/'+id;

var $thisForm = $('#form-gallery-edit');
var $thisModal = $('#modal-edit-gallery');

$thisModal.find('.modal-body .alert-danger, .modal-body .alert-success').remove();

$.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: "GET",
    encoding:"UTF-8",
    url: url,
    dataType:'json',

    error: function() {
        $('#wraper_ajax').remove();
        var message = '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
        $thisModal.find('.modal-body').prepend(message);
    },
    success: function(response) {
        $('#wraper_ajax').remove();
        $thisForm.find('input[name="id"]').val(response.id);
        $thisForm.find('.gallery-product').attr('href', response.href);
        $thisForm.find('.gallery-product img').attr('src', response.src);

        if(response.active == 1){
            $thisForm.find('input[name="active"]').parent().removeClass('switch-on switch-off').addClass('switch-on');
        } else {
            $thisForm.find('input[name="active"]').parent().removeClass('switch-on switch-off').addClass('switch-off');
        }
    }
});
});

//------------------------------------------------------------------------------------

jQuery(document).on("click", ".delete-register-gallery", function() {
$('body').deleteRegister(jQuery(this), 'productsGalleries', 5, 'galleries');
});

//------------------------------------------------------------------------------------

jQuery(document).on("click", ".change-status-gallery", function() {
$('body').changeStatus(jQuery(this), 'productsGalleries');
});
        </script>
    </x-slot>
</x-app-layoutt>

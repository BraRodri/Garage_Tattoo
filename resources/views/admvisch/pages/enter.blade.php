<x-app-layoutt>

    <ol class="breadcrumb bc-2">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <a href="<?php echo BASE_URL . $module; ?>"><?php echo $title;
                ?></a>
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


    <div class="row">
        <div class="col-md-12">

            <form role="form" id="form1" method="post" action="{{ route('pages.insert') }}"
                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                @csrf
                <ul class="nav nav-tabs left-aligned">
                    <!-- available classes "bordered", "right-aligned" -->
                    <li class="active"><a href="#tab1" data-toggle="tab">
                            <span><i class="fa fa-text-height" aria-hidden="true"></i></span>
                            <span>Página</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
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
                                    <label class="control-label">Página</label>
                                    <input type="text" class="form-control required" name="title" id="title"
                                        maxlength="255" />
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Introducción</label>
                                    <textarea class="form-control ckeditor" name="introduction"
                                        id="introduction"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Cuerpo</label>
                                    <textarea class="form-control ckeditor" name="description"
                                        id="description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Activar Registro?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="active" id="active" value="1"
                                                checked="checked">
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
                                    <label class="control-label">Título</label>
                                    <input type="text" class="form-control" name="meta_title" id="meta_title"
                                        maxlength="255" />
                                    <label class="formNote">
                                        * Titulo de la página: es el título que deseamos que aparezca en el buscador.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Autor</label>
                                    <input type="text" class="form-control" name="meta_author" id="meta_author"
                                        maxlength="100" />
                                    <label class="formNote">
                                        * Autor: El nombre del webmaster o de la empresa autora de la página web.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <input type="text" class="form-control" name="meta_description"
                                        id="meta_description" />
                                    <label class="formNote">
                                        * Explica con un par de frases el contenido de la web.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Palabras Claves (Keywords)</label>
                                    <textarea class="form-control" name="meta_keyword" id="meta_keyword"
                                        rows="5"></textarea>
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
                    <a href="{{route('pages')}}"
                        type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                </div>

            </form>

        </div>
    </div>

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
            });

        </script>
    </x-slot>
</x-app-layoutt>

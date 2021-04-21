<x-app-layoutt>

    <ol class="breadcrumb bc-2">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <?php echo $parent_title; ?>
        </li>
        <li>
            <a href="<?php echo BASE_URL . $module; ?>"><?php echo $title;
                ?></a>
        </li>
        <li class="active">
            <strong>Editar Registro</strong>
        </li>
    </ol>

    <h3><?php echo $title; ?></h3>
    <br />

    <div class="clearfix"></div>

    @if (Session::has('error'))
        @if (Session::get('error') == 'success')
            <div class="alert alert-success"><strong>OK!</strong> Proceso realizado correctamente.</div>
        @endif
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
            <div class="panel-title">Formulario de Modificación</div>
        </div>

        <div class="panel-body color-gris-fondo">

            <form role="form" id="form1" method="post" action="{{ route('metadata.update') }}"
                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                @csrf
                <div class="form-group">
                    <label class="control-label">¿Activar URL amigables?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="friendly_url" id="friendly_url" value="1" <?php
                                if ($metadata->friendly_url == 1) {
                            echo "checked='checked'";
                            } ?>>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="control-label">Título</label>
                    <input type="text" class="form-control" name="title" id="title" maxlength="255"
                        value="<?php echo $metadata->title; ?>" />
                    <label class="formNote">
                        * Titulo de la página: es el título que deseamos que aparezca en el buscador.
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Autor</label>
                    <input type="text" class="form-control" name="authors" id="authors" maxlength="100"
                        value="<?php echo $metadata->authors; ?>" />
                    <label class="formNote">
                        * Autor: El nombre del webmaster o de la empresa autora de la página web.
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Asunto</label>
                    <input type="text" class="form-control" name="subject" id="subject" maxlength="100"
                        value="<?php echo $metadata->subject; ?>" />
                    <label class="formNote">
                        * Indica de que trata tu web: Negocios, Ocio, Juegos, Moviles, etc.
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Descripción</label>
                    <input type="text" class="form-control" name="description" id="description"
                        value="<?php echo $metadata->description; ?>" />
                    <label class="formNote">
                        * Explica con un par de frases el contenido de la web.
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Palabras Claves (Keywords)</label>
                    <textarea class="form-control" name="keyword" id="keyword"
                        rows="5"><?php echo $metadata->keyword; ?></textarea>
                    <label class="formNote">
                        * Palabras que expecifiquen el contenido de la web. Por ejemplo: juegos, juegos online, etc.
                        (max: 200 caracteres).
                    </label>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Lenguaje</label>
                        <select class="form-control" name="language" id="language">
                            <option value="">Seleccionar</option>
                            <option value="Spanish" <?php echo $metadata->language == 'Spanish' ?
                                "selected='selected'" : ''; ?>>Español</option>
                            <option value="English" <?php echo $metadata->language == 'English' ?
                                "selected='selected'" : ''; ?>>Ingles</option>
                        </select>
                        <div class="clearfix"></div>
                        <label class="formNote">
                            * Acción del motor - Especifica la acción del motor de búsqueda.
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Revisit-After</label>
                    <input type="text" class="form-control" name="indexing" id="indexing" maxlength="50"
                        value="<?php echo $metadata->indexing; ?>" />
                    <label class="formNote">
                        * Intervalo de tiempo para que los buscadores vuelvan a analizar la web. Ej: 1 days
                    </label>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Robots</label>
                        <select class="form-control" name="robots" id="robots">
                            <option value="">Seleccionar</option>
                            <option value="index,follow" <?php echo $metadata->robots == 'index,follow' ?
                                "selected='selected'" : ''; ?>>Indexar la página y
                                seguir los enlaces</option>
                            <option value="index,nofollow" <?php echo $metadata->robots ==
                                'index,nofollow' ? "selected='selected'" : ''; ?>>Indexar la página y no
                                seguir los enlaces</option>
                            <option value="noindex,follow" <?php echo $metadata->robots ==
                                'noindex,follow' ? "selected='selected'" : ''; ?>>No indexar la página y
                                seguir los enlaces</option>
                            <option value="noindex,nofollow" <?php echo $metadata->robots ==
                                'noindex,nofollow' ? "selected='selected'" : ''; ?>>No indexar la página
                                y no seguir los enlaces</option>
                        </select>
                        <div class="clearfix"></div>
                        <label class="formNote">
                            * Acción del motor - Especifica la acción del motor de búsqueda.
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Googlebots</label>
                        <select class="form-control" name="googlebots" id="googlebots">
                            <option value="">Seleccionar</option>
                            <option value="archive" <?php echo $metadata->googlebots == 'archive' ?
                                "selected='selected'" : ''; ?>>Permitir a Google guardar copia de la web
                            </option>
                            <option value="noarchive" <?php echo $metadata->googlebots == 'noarchive' ?
                                "selected='selected'" : ''; ?>>No permitir a Google
                                guardar copia de la web</option>
                        </select>
                        <div class="clearfix"></div>
                        <label class="formNote">
                            * Indica al crawler de google como debe proceder.
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Distribución</label>
                        <select class="form-control" name="distribution" id="distribution">
                            <option value="">Seleccionar</option>
                            <option value="Global" <?php echo $metadata->distribution == 'Global' ?
                                "selected='selected'" : ''; ?>>Global</option>
                            <option value="Local" <?php echo $metadata->distribution == 'Local' ?
                                "selected='selected'" : ''; ?>>Local</option>
                        </select>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Código Verificación Google</label>
                    <input type="text" class="form-control" name="googlecode" id="googlecode" maxlength="100"
                        value="<?php echo $metadata->googlecode; ?>" />
                </div>

                <div class="form-group">
                    <label class="control-label">Código Google Analitycs</label>
                    <input type="text" class="form-control" name="analyticcode" id="analyticcode" maxlength="100"
                        value="<?php echo $metadata->analyticcode; ?>" />
                    <label class="formNote">
                        * Sólo código: UA-XXX...
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Código Píxeles Facebook</label>
                    <input type="text" class="form-control" name="pixelcode" id="pixelcode" maxlength="100"
                        value="<?php echo $metadata->pixelcode; ?>" />
                    <label class="formNote">
                        * Sólo agregar ID Píxeles.
                    </label>
                </div>

                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{ route('metadata') }}" type="button" class="btn btn-primary"><i
                            class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                    <input type="hidden" name="id"
                        value="<?php echo $metadata->id; ?>" />
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
                    }
                });

                $('input[name=modificar_clave]').change(function() {
                    if ($(this).is(':checked')) {
                        $('input[name=clave]').addClass('required');
                    } else {
                        $('input[name=clave]').removeClass('required');
                    }
                });
            });

        </script>
    </x-slot>
</x-app-layoutt>

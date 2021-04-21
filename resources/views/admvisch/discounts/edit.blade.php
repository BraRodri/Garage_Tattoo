<x-app-layoutt>


    @section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection
    <ol class="breadcrumb bc-2" >
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
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
        @if (Session::get('error') == 'selected')
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, no seleccionaste los datos
                que se validarán al descuento.</div>
        @endif
        @if (Session::get('error') == 'discount')
            <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, debes al menos indicar un
                valor para el descuento el cual puede ser en porcentaje o pesos.</div>
        @endif
    @endif
    
    <div class="panel panel-primary">
    
        <div class="panel-heading container-blue">
            <div class="panel-title">Formulario de Modificación</div>
        </div>
    
        <div class="panel-body color-gris-fondo">
    
            <form role="form" id="form1" method="post" action="{{route('discounts.update')}}" enctype="multipart/form-data" class="custom-validate form-groups-bordered">
    
                @csrf
                <div class="form-group">
                    <label class="control-label">Descripción</label>
                    <input type="text" class="form-control required" name="title" id="title" maxlength="255" value="<?php echo $discount->title; ?>" />
                </div>
    
                <div class="form-group">
                    <label class="control-label">Nombre del Cupón</label>
                    <input type="text" class="form-control required" name="code" id="code" maxlength="255" value="<?php echo $discount->code; ?>" />
                    <label class="formNote">* Ingresar en mayúsculas, sin espacios, sin acentos y sin caracteres especiales. Ejemplo: CUPON2020</label>
                </div>
    
                <div class="form-group">
                    <label class="control-label">Descuento %</label>
                    <input type="text" class="form-control" name="discount_percentage" id="discount_percentage" value="<?php echo ($discount->discount_percentage > 0)? $discount->discount_percentage : ''; ?>" />
                    <label class="formNote">* Para indicar decimales, estos deben ser expresados con un punto.</label>
                </div>
    
                <div class="form-group">
                    <label class="control-label">Descuento $</label>
                    <input type="text" class="form-control digits" name="discount" id="discount" value="<?php echo ($discount->discount > 0)? $discount->discount : ''; ?>" />
                </div>
    
                <div class="form-group">
                    <label class="control-label">Descuento Desde</label>
                    <input type="text" class="form-control datepicker-start-daysBeforeDisabled required" name="start_date" id="start_date" value="<?php echo $discount->start_date; ?>" />
                </div>
    
                <div class="form-group">
                    <label class="control-label">Descuento Hasta</label>
                    <input type="text" class="form-control datepicker-end-daysBeforeDisabled required" name="end_date" id="end_date" value="<?php echo $discount->end_date; ?>" />
                </div>
    
                <div class="form-group">
                    <label class="control-label">Texto Restricciones</label>
                    <textarea class="form-control required" name="restrictions" id="restrictions" rows="5"><?php echo $discount->restrictions; ?></textarea>
                    <label class="formNote">* Indicar texto para explicar el uso y restricciones que aplica el cupón de descuento.</label>
                </div>
    
                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Tipo</label>
                        <select class="form-control required" name="type" id="type">
                            <option value="">Seleccionar</option>
                            <?php
                            if(count($types) > 0) {
                                foreach ($types AS $key => $type) {
                                    $selected = (isset($discount->type) && !empty($discount->type) && $discount->type == $key)? 'selected="selected"' : '';
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $type; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
    
                <div class="form-group container-type-discount hidden">
                    <select multiple="multiple" size="10" name="relations[]" class="dual-list-box"></select>
                </div>
    
                <div class="form-group">
                    <label class="control-label">¿Enviar Cupón?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="send" id="send" value="1" <?php if($discount->send == 1){ echo "checked='checked'"; } ?>>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <label class="formNote">* Esta opción le permite enviar masivamente el cupón a todos sus clientes o bien a los seleccionados mediante el tipo "clientes".</label>
                    <div class="clearfix"></div>
                </div>
    
                <div class="form-group">
                    <label class="control-label">¿Activar Registro?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="active" id="active" value="1" <?php if($discount->active == 1){ echo "checked='checked'"; } ?>>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('discounts')}}" type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y Volver</a>
                    <input type="hidden" name="id" value="<?php echo $discount->id; ?>" />
                </div>
    
            </form>
    
        </div>
    
    </div>
    <input type="text" value="{{csrf_token()}}" name="_token" hidden>

    
    
    <br />
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
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('validate-has-error');
                },
                errorPlacement: function (error, element)
                {
                    if(element.attr("class").split(" ")[1] == 'multiselect'){
                        error.insertAfter(element.parent().parent().parent());
                    } else {
                        if (element.closest('.has-switch').length) {
                            error.insertAfter(element.closest('.has-switch'));
                        } else if (element.parent('.checkbox, .radio').length || element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                }
            });

            var loadSelectType = function(type){
                if(type == '') {
                    $('.container-type-discount').addClass('hidden');
                } else if(type == 'Clients' || type == 'Categories' || type == 'Brands' || type == 'Products'){
                    $('.container-type-discount').removeClass('hidden');

                    $('.dual-list-box').bootstrapDualListbox('destroy');
                    $('.dual-list-box').empty();
                    var contenedor = $('.dual-list-box').parent();
                    $('.dual-list-box').remove();
                    contenedor.append('<select multiple="multiple" size="10" name="relations[]" class="dual-list-box"></select>');

                    var url = "{{route('discounts.get".type."',)}}" ;
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

                            var isRequiredField = dual_list_box2.attr('required');

                            function initDualListBox() {
                                var instance = dual_list_box2.data('plugin_bootstrapDualListbox');
                                var nonSelectedList = instance.elements.select2;
                                var isDualListBoxValidated = !(instance.selectedElements > 0);

                                nonSelectedList.prop('required', isDualListBoxValidated);
                                instance.elements.originalSelect.prop('required', false);

                                if(isDualListBoxValidated == false){
                                    $('select.dual-list-box-1').parent().removeClass('validate-has-error');
                                }
                            }

                            dual_list_box2.change(function () {
                                if(!isRequiredField) {
                                    initDualListBox();
                                }
                            });

                            if(!isRequiredField) {
                                initDualListBox();
                            }
                        }
                    });
                } else {
                    $('.container-type-discount').addClass('hidden');
                }
            };

            $('select[name=type]').change(function(){
                var type = $(this).find(':selected').val();
                loadSelectType(type);
            });

            var typeSelected = $('select[name="type"]').find(':selected').val();
            loadSelectType(typeSelected);
            });
        </script>
    </x-slot>
</x-app-layoutt>
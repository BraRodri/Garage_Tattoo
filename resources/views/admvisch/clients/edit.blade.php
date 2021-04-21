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

    <form role="form" id="form1" method="post"
        action="{{route('clients.update')}}"
        enctype="multipart/form-data" class="custom-validate form-groups-bordered">

        @csrf
        <div class="row">
            <div class="col-md-12">

                <ul class="nav nav-tabs left-aligned">
                    <!-- available classes "bordered", "right-aligned" -->
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            <span><i class="fa fa-user" aria-hidden="true"></i></span>
                            <span>Cliente</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <span><i class="fa fa-file-text" aria-hidden="true"></i></span>
                            <span>Datos de Facturación</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="#tab3" data-toggle="tab">
                            <span><i class="fa fa-truck" aria-hidden="true"></i></span>
                            <span>Libreta de Direcciones</span>
                        </a>
                    </li>
                   
                </ul>

                <div class="tab-content tab-validate">
                    <div class="tab-pane active" id="tab1">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Tipo Cliente</label>
                                        <select class="form-control required" name="type" id="type">
                                            <option value="">Seleccionar</option>
                                            <option value="PERSONA" <?php echo isset($client['type']) && !empty($client['type']) &&
                                                $client['type'] == 'PERSONA' ? 'selected="selected"' : ''; ?>>Persona</option>
                                            <option value="EMPRESA" <?php echo isset($client['type']) && !empty($client['type']) &&
                                                $client['type'] == 'EMPRESA' ? 'selected="selected"' : ''; ?>>Empresa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Rut</label>
                                    <input type="text" class="form-control required rut" name="rut" id="rut"
                                        placeholder="11.111.111-1" maxlength="12"
                                        value="<?php echo $client['rut']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Razón Social</label>
                                    <input type="text" class="form-control required" name="business_name"
                                        id="business_name" maxlength="255"
                                        value="<?php echo $client['business_name']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Giro</label>
                                    <input type="text" class="form-control" name="commercial_business"
                                        id="commercial_business" maxlength="255"
                                        value="<?php echo $client['commercial_business']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Teléfono</label>
                                    <input type="text" class="form-control required" name="phone" id="phone"
                                        maxlength="50"
                                        value="<?php echo $client['phone']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control required email" name="email" id="email"
                                        maxlength="100"
                                        value="<?php echo $client['email']; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Modificar Clave?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="modificate_password" id="modificate_password"
                                                value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">
                                        * Al dejar sin selección la modificación de clave no afectará crear o modificar
                                        la clave existente.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Clave Automática?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="generate_password" id="generate_password"
                                                value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">
                                        * Al seleccionar generación de clave automática, no será necesario completar
                                        clave.
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Clave</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        maxlength="255" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Activar Registro?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="active" id="active" value="1" <?php if ($client['active'] == 1) {
                                            echo "checked='checked'";
                                            } ?>>
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
                                    <label class="control-label">Dirección</label>
                                    <input type="text" class="form-control" name="document_address"
                                        id="document_address" maxlength="255"
                                        value="<?php echo $client['address']; ?>" />
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label">N°</label>
                                                <input type="text" class="form-control" name="document_address_number"
                                                    id="document_address_number" maxlength="100"
                                                    value="<?php echo $client['address_number']; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label">N° Depto / Oficina</label>
                                                <input type="text" class="form-control" name="document_office_number"
                                                    id="document_office_number" maxlength="100"
                                                    value="<?php echo $client['office_number']; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="form-group content-width-full">
                                                <div class="input-group content-width-full">
                                                    <label class="control-label">Región</label>
                                                    <select class="form-control" name="document_regions_id"
                                                        id="document_regions_id">
                                                        <option value="">Seleccionar</option>
                                                        <?php 
                                                        if (is_array($regions) || is_object($regions)){

                                                        foreach ($regions as $region) {
                                                        $selected = isset($client['regions_id']) &&
                                                        !empty($client['regions_id']) &&
                                                        $client['regions_id'] == $region['id'] ?
                                                        'selected="selected"' : ''; ?>
                                                        <option
                                                            value="<?php echo $region['code']; ?>"
                                                            <?php echo $selected;?>><?php echo $region['code_internal'] . ' - Región de '
                                                        . $region['description']; ?></option>
                                                        <?php
                                                        }
                                                    }
                                                         ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="form-group content-width-full">
                                                <div class="input-group content-width-full">
                                                    <label class="control-label">Provincia</label>
                                                    <select class="form-control" name="document_provinces_id"
                                                        id="document_provinces_id">
                                                        <option value="">Seleccionar</option>
                                                        <?php 
                                                        if (is_array($provinces) || is_object($provinces)){

                                                        foreach ($provinces as $province) {
                                                        $selected = isset($client['provinces_id']) &&
                                                        !empty($client['provinces_id']) &&
                                                        $client['provinces_id'] == $province['id'] ?
                                                        'selected="selected"' : ''; ?>
                                                        <option
                                                            value="<?php echo $province['code']; ?>"
                                                            <?php echo $selected; ?>><?php echo $province['description']; ?></option>
                                                        <?php
                                                        }
                                                        }
                                                         ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="form-group content-width-full">
                                                <div class="input-group content-width-full">
                                                    <label class="control-label">Comuna</label>
                                                    <select class="form-control" name="document_locations_id"
                                                        id="document_locations_id">
                                                        <option value="">Seleccionar</option>
                                                        <?php 
                                                         if (is_array($locations) || is_object($locations)){
                                                        foreach ($locations as $location) {
                                                        $selected = isset($client['locations_id']) &&
                                                        !empty($client['locations_id']) &&
                                                        $client['locations_id'] == $location['id'] ?
                                                        'selected="selected"' : ''; ?>
                                                        <option
                                                            value="<?php echo $location['code']; ?>"
                                                            <?php echo $selected;?>><?php echo $location['description']; ?></option>
                                                        <?php
                                                        }
                                                    }
                                                         ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="formNote">
                                        * Los datos adicionales de rut, razón social y giro serán tomados de los datos
                                        de cliente.
                                    </label>
                                </div>

                            </div>

                        </div>

                    </div>

               
                
                    <div class="tab-pane" id="tab3">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <button type="button" class="btn btn-orange" id="show-form-address"><i
                                            class="fa fa-plus" aria-hidden="true"></i> Agregar</button>
                                </div>

                            </div>

                        </div>

                        <table class="table table-bordered responsive" id="table-address">
                            <thead>
                                <tr>
                                    <th>Dirección</th>
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
               

                </div>
                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('clients')}}"
                        type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                    <input type="hidden" name="id"
                        value="<?php echo $client['id']; ?>" />
                </div>

            </div>
        </div>

    </form>

    <input type="text" value="{{csrf_token()}}" name="_token" hidden>
    <br />

    <div class="modal fade" id="modal-add-address">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
    
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Ingreso de Dirección</h4>
                </div>
    
                <form role="form" id="form-address-add" method="post" class="custom-validate">
    
                    <div class="modal-body">
    
                        <div class="row">
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Dirección</label>
                                    <input type="text" class="form-control required" name="address" id="address" maxlength="255" />
                                </div>
                            </div>
    
                        </div>
    
                        <div class="row">
    
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">N°</label>
                                    <input type="text" class="form-control required" name="address_number" id="address_number" />
                                </div>
                            </div>
    
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">N° Depto / Oficina</label>
                                    <input type="text" class="form-control" name="office_number" id="office_number" />
                                </div>
                            </div>
    
                        </div>
    
                        <div class="row">
    
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Región</label>
                                        <select class="form-control required" name="regions_id" id="regions_id">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            
                                            if (is_array($regions) || is_object($regions)){
                                                foreach ($regions AS $region) {
                                                    ?>
                                                    <option value="<?php echo $region['code']; ?>"><?php echo $region['code_internal'] . ' - Región de ' . $region['description']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <div class="input-group" style="width:100%">
                                        <label class="control-label">Provincia</label>
                                        <select class="form-control required" name="provinces_id" id="provinces_id">
    
                                        </select>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <div class="input-group" style="width:100%">
                                        <label class="control-label">Comuna</label>
                                        <select class="form-control required" name="locations_id" id="locations_id">
    
                                        </select>
                                    </div>
                                </div>
                            </div>
    
                        </div>
    
                        <div class="row">
    
                            <div class="col-md-6 col-sm-12">
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
    
                    </div>
    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-info">Guardar Dirección</button>
                        <input type="hidden" name="client_id" id="client_id" value="<?php echo $client->id; ?>" />
                    </div>
    
                </form>
    
            </div>
        </div>
    </div>
    
    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
    
    <div class="modal fade" id="modal-edit-address">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
    
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Actualización de Dirección</h4>
                </div>
    
                <form role="form" id="form-address-edit" method="post" class="custom-validate">
                   @csrf
                    <div class="modal-body">
    
                        <div class="row">
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Dirección</label>
                                    <input type="text" class="form-control required" name="address" id="address" maxlength="255" />
                                </div>
                            </div>
    
                        </div>
    
                        <div class="row">
    
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">N°</label>
                                    <input type="text" class="form-control required" name="address_number" id="address_number" />
                                </div>
                            </div>
    
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">N° Depto / Oficina</label>
                                    <input type="text" class="form-control" name="office_number" id="office_number" />
                                </div>
                            </div>
    
                        </div>
    
                        <div class="row">
    
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="control-label">Región</label>
                                        <select class="form-control required" name="regions_id" id="regions_id">
                                            <option value="">Seleccionar</option>
                                            <?php
                                           if (is_array($regions) || is_object($regions)){
                                                foreach ($regions AS $region) {
                                                    ?>
                                                    <option value="<?php echo $region['code']; ?>"><?php echo $region['code_internal'] . ' - Región de ' . $region['description']; ?></option>
                                                    <?php
                                                }
                                            }
                                            
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <div class="input-group" style="width:100%">
                                        <label class="control-label">Provincia</label>
                                        <select class="form-control required" name="provinces_id" id="provinces_id">
    
                                        </select>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <div class="input-group" style="width:100%">
                                        <label class="control-label">Comuna</label>
                                        <select class="form-control required" name="locations_id" id="locations_id">
    
                                        </select>
                                    </div>
                                </div>
                            </div>
    
                        </div>
    
                        <div class="row">
    
                            <div class="col-md-6 col-sm-12">
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
    
                    </div>
                    <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-info">Guardar Dirección</button>
                        <input type="hidden" name="client_id" id="client_id" value="<?php echo $client->id; ?>" />
                        <input type="hidden" name="id" id="id" value="" />
                    </div>
    
                </form>
    
            </div>
        </div>
    </div>
    <input type="text" value="{{csrf_token()}}" name="_token" hidden>

    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
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

                $("#form1 select[name=type]").change(function() {
                    if ($(this).val() == 'EMPRESA') {
                        $('#commercial_business').rules('add', {
                            required: true
                        });
                    } else {
                        $('#commercial_business').rules('remove');
                    }
                });

                //------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                // MODULOS DIRECCIONES DE CLIENTE
                //------------------------------------------------------------------------------------------------------------------------------------------------------------------------

                $.fn.loadDataTableClientsAddresses = function() {

                    var url = "{{route('clientsAddress')}}";
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
                            $('#table-address tbody').html(response.data);
                        }
                    });
                };

                $('#form-address-add').validate({
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
                        } else if (element.parent('.checkbox, .radio').length || element.parent(
                                '.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(ev) {

                        var $thisForm = $('#form-address-add');
                        var $thisModal = $('#modal-add-address');

                        var url = "{{route('clientsAddress.insert')}}";
                        var active = 0;

                        if ($thisForm.find('input[name="active"]:checked').val() == 1) {
                            active = 1;
                        }

                        var data = {
                            clients_id: $thisForm.find('input[name="client_id"]').val(),
                            address: $thisForm.find('input[name="address"]').val(),
                            address_number: $thisForm.find('input[name="address_number"]')
                            .val(),
                            office_number: $thisForm.find('input[name="office_number"]').val(),
                            regions_id: $thisForm.find('select[name="regions_id"]').find(
                                ':selected').val(),
                            provinces_id: $thisForm.find('select[name="provinces_id"]').find(
                                ':selected').val(),
                            locations_id: $thisForm.find('select[name="locations_id"]').find(
                                ':selected').val(),
                            active: active
                        }

                        $thisModal.find('.modal-body .alert-danger, .modal-body .alert-success')
                            .remove();

                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            type: "POST",
                            encoding: "UTF-8",
                            url: url,
                            data: data,
                            dataType: 'json',
                           
                            error: function() {
                                $('#wraper_ajax').remove();
                                var message =
                                    '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                                $thisModal.find('.modal-body').prepend(message);
                            },
                            success: function(response) {
                                $('#wraper_ajax').remove();
                                if (response.error == 0) {
                                    if (response.type == 'duplicate') {
                                        var message =
                                            '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, la dirección que intenta ingresar ya se encuentra registrado.</div>';
                                        $thisModal.find('.modal-body').prepend(message);
                                    } else if (response.type == 'failure') {
                                        var message =
                                            '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                                        $thisModal.find('.modal-body').prepend(message);
                                    } else {
                                        $thisForm[0].reset();

                                        var message =
                                            '<div class="alert alert-success"><strong>OK!</strong> ingreso realizado correctamente.</div>';
                                        $thisModal.find('.modal-body').prepend(message);

                                        setTimeout(function() {
                                            $thisModal.find(
                                                '.modal-body .alert-danger, .modal-body .alert-success'
                                                ).remove();
                                            $thisModal.modal('hide');
                                        }, 1000);

                                        $('body').loadDataTableClientsAddresses();
                                    }
                                }
                            }
                        });
                    }
                });

                $('#form-address-edit').validate({
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
                        } else if (element.parent('.checkbox, .radio').length || element.parent(
                                '.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(ev) {

                        var $thisForm = $('#form-address-edit');
                        var $thisModal = $('#modal-edit-address');

                        var url = "{{route('clientsAddress.update')}}";
                        var active = 0;

                        if ($thisForm.find('input[name="active"]:checked').val() == 1) {
                            active = 1;
                        }

                        var data = {
                            id: $thisForm.find('input[name="id"]').val(),
                            clients_id: $thisForm.find('input[name="client_id"]').val(),
                            address: $thisForm.find('input[name="address"]').val(),
                            address_number: $thisForm.find('input[name="address_number"]')
                            .val(),
                            office_number: $thisForm.find('input[name="office_number"]').val(),
                            regions_id: $thisForm.find('select[name="regions_id"]').find(
                                ':selected').val(),
                            provinces_id: $thisForm.find('select[name="provinces_id"]').find(
                                ':selected').val(),
                            locations_id: $thisForm.find('select[name="locations_id"]').find(
                                ':selected').val(),
                            active: active
                        }

                        $thisModal.find('.modal-body .alert-danger, .modal-body .alert-success')
                            .remove();

                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            type: "POST",
                            encoding: "UTF-8",
                            url: url,
                            data: data,
                            dataType: 'json',
                           
                            error: function() {
                                $('#wraper_ajax').remove();
                                var message =
                                    '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                                $thisModal.find('.modal-body').prepend(message);
                            },
                            success: function(response) {
                                $('#wraper_ajax').remove();
                                if (response.error == 0) {
                                    if (response.type == 'duplicate') {
                                        var message =
                                            '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, la dirección que intenta actualizar ya se encuentra registrado.</div>';
                                        $thisModal.find('.modal-body').prepend(message);
                                    } else if (response.type == 'failure') {
                                        var message =
                                            '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                                        $thisModal.find('.modal-body').prepend(message);
                                    } else {
                                        $thisForm[0].reset();

                                        var message =
                                            '<div class="alert alert-success"><strong>OK!</strong> actualización realizada correctamente.</div>';
                                        $thisModal.find('.modal-body').prepend(message);

                                        setTimeout(function() {
                                            $thisModal.find(
                                                '.modal-body .alert-danger, .modal-body .alert-success'
                                                ).remove();
                                            $thisModal.modal('hide');
                                        }, 1000);

                                        $('body').loadDataTableClientsAddresses();
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
                    var url = "{{route('clientsAddress.status')}}";
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
                };

                $.fn.deleteRegister = function(thisElement, module, numberTab, nameTable) {

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
                        callback: function(result) {
                            if (result == true) {

                                var url = '<?php echo BASE_URL?>'+'clientsAddress/delete/' + id;

                                $('#tab' + numberTab + ' .alert-danger, #tab' + numberTab +
                                    ' .alert-success').remove();

                                $.ajax({
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    type: "GET",
                                    encoding: "UTF-8",
                                    url: url,
                                    dataType: 'json',
                                    
                                    error: function() {
                                        $('#wraper_ajax').remove();
                                        var message =
                                            '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                                        $(message).insertBefore('#tab' + numberTab +
                                            ' #table-' + nameTable);
                                    },
                                    success: function(response) {
                                        $('#wraper_ajax').remove();
                                        if (response.type == 'success') {
                                            var message =
                                                '<div class="alert alert-success"><strong>OK!</strong> registro eliminado correctamente.</div>';
                                        } else {
                                            var message =
                                                '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                                        }
                                        $(message).insertBefore('#tab' + numberTab +
                                            ' #table-' + nameTable);

                                        if (module == 'clientsAddress') {
                                            $('body')
                                            .loadDataTableClientsAddresses();
                                        } else {
                                            //$('body').loadDataTableProductsArchives();
                                        }
                                    }
                                });
                            }
                        }
                    });
                };

                $('body').loadDataTableClientsAddresses();

            });

            //-----------------------------------------------------------------------------------

            jQuery(document).on('click', '#show-form-address', function(e) {
                e.preventDefault();
                $('#modal-add-address').modal('show', {
                    backdrop: 'static'
                });
            });

            //------------------------------------------------------------------------------------

            jQuery(document).on('click', '.show-form-edit-address', function(e) {
                e.preventDefault();
                $('#modal-edit-address').modal('show', {
                    backdrop: 'static'
                });

                var id = $(this).attr('id');
                var url = '<?php echo BASE_URL?>'+'clientsAddress/edit/' + id ;

                var $thisForm = $('#form-address-edit');
                var $thisModal = $('#modal-edit-address');

                $thisModal.find('.modal-body .alert-danger, .modal-body .alert-success').remove();

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: "GET",
                    encoding: "UTF-8",
                    url: url,
                    dataType: 'json',
                    
                    error: function() {
                        $('#wraper_ajax').remove();
                        var message =
                            '<div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
                        $thisModal.find('.modal-body').prepend(message);
                    },
                    success: function(response) {
                        $('#wraper_ajax').remove();
                        $thisForm.find('input[name="id"]').val(response.id);
                        $thisForm.find('input[name="address"]').val(response.address);
                        $thisForm.find('input[name="address_number"]').val(response.address_number);
                        $thisForm.find('input[name="office_number"]').val(response.office_number);
                        $thisForm.find('select[name="regions_id"] option[value="' + response
                            .regions_id + '"]').attr("selected", "selected");

                        $('select[name=provinces_id]').load('<?php echo BASE_URL?>'+'clients/provinces/' + response.regions_id,
                            function() {
                                $thisForm.find('select[name="provinces_id"] option[value="' +
                                    response.provinces_id + '"]').attr("selected",
                                    "selected");
                            });
                        $('select[name=locations_id]').load('<?php echo BASE_URL?>'+'clients/locations/' + response.provinces_id,
                            function() {
                                $thisForm.find('select[name="locations_id"] option[value="' +
                                    response.locations_id + '"]').attr("selected",
                                    "selected");
                            });

                        if (response.active == 1) {
                            $thisForm.find('input[name="active"]').parent().removeClass(
                                'switch-on switch-off').addClass('switch-on');
                        } else {
                            $thisForm.find('input[name="active"]').parent().removeClass(
                                'switch-on switch-off').addClass('switch-off');
                        }
                    }
                });
            });

            //------------------------------------------------------------------------------------

            jQuery(document).on("click", ".delete-register-address", function() {
                $('body').deleteRegister(jQuery(this), 'clientsAddress', 2, 'address');
            });

            //------------------------------------------------------------------------------------

            jQuery(document).on("click", ".change-status-address", function() {
                $('body').changeStatus(jQuery(this), 'clientsAddress');
            });
            //------------------------------------------------------------------------------------

            jQuery('select[name=regions_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=provinces_id]').html("");
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=provinces_id]').load('<?php echo BASE_URL?>'+'clients/provinces/' + code);
            });

            jQuery('select[name=provinces_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=locations_id]').html("");
                jQuery('select[name=locations_id]').load('<?php echo BASE_URL?>'+'clients/locations/' + code );
            });

            jQuery('select[name=document_regions_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=document_provinces_id]').html("");
                jQuery('select[name=document_locations_id]').html("");
                jQuery('select[name=document_provinces_id]').load('<?php echo BASE_URL?>'+'clients/provinces/' + code);
            });

            jQuery('select[name=document_provinces_id]').change(function() {
                var code = jQuery(this).find(':selected').val();
                jQuery('select[name=document_locations_id]').html("");
                jQuery('select[name=document_locations_id]').load('<?php echo BASE_URL?>'+'clients/locations/' + code);
            });

        </script>
    </x-slot>
</x-app-layoutt>

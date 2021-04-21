<x-app-layoutt>

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
    
    <div class="panel panel-primary">
    
        <div class="panel-heading container-blue">
            <div class="panel-title">Formulario de Modificación</div>
        </div>
    
        <div class="panel-body color-gris-fondo">
    
            <form role="form" id="form1" method="post" action="{{route('users.update')}}" enctype="multipart/form-data" class="custom-validate form-groups-bordered">
    
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <label class="control-label">Perfil</label>
                        <select class="form-control required" name="roles_id" id="roles_id">
                            <option value="">Seleccionar</option>
                            <?php
                            
                                foreach ($roles AS $rol) {
                                    ?>
                                    <option value="<?php echo $rol->name; ?>" 
                                    <?php 
                                   
                                        if($user->getRoleNames()->get(0)  == $rol->name){
                                            echo 'selected="selected"'; 
                                        }
                                        
                                    
                                    ?>><?php echo $rol->name; ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
    
                <div class="form-group">
                    <label class="control-label">Rut</label>
                    <input type="text" class="form-control required rut" name="rut" id="rut" placeholder="11.111.111-1" maxlength="12" value="<?php echo $user['rut']; ?>" />
                </div>
    
                <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control required" name="name" id="name" maxlength="255" value="<?php echo $user['name']; ?>" />
                </div>
    
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control required email" name="email" id="email" maxlength="100" value="<?php echo $user['email']; ?>" />
                </div>
    
              
    
                <div class="form-group">
                    <label class="control-label">¿Modificar Clave?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="change_password" id="change_password" value="1">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
    
                <div class="form-group">
                    <label class="control-label">Clave</label>
                    <input type="password" class="form-control" name="password" id="password" maxlength="255" value="" />
                </div>
    
                <div class="form-group">
                    <label class="control-label">¿Activar Registro?</label>
                    <div class="col-md-12 no-padding">
                        <div id="label-switch" class="make-switch" data-on-label="SI" data-off-label="NO">
                            <input type="checkbox" name="active" id="active" value="1" <?php if($user['active'] == 1){ echo "checked='checked'"; } ?>>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
    
                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('users')}}" type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y Volver</a>
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
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

<x-app-layoutt>

    <ol class="breadcrumb bc-2">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <?php echo $parent_title; ?>
        </li>
        <li>
            <a href="{{ route('roles') }}"><?php echo $title; ?></a>
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


    <div class="panel panel-primary">

        <div class="panel-heading container-blue">
            <div class="panel-title">Formulario de Ingreso</div>
        </div>

        <div class="panel-body color-gris-fondo">

            <form role="form" id="form1" method="post" action="{{ route('roles.update') }}"
                enctype="multipart/form-data" class="custom-validate form-groups-bordered">

                @csrf
                <div class="form-group">
                    <label class="control-label">Descripción</label>
                    <input type="text" class="form-control required" name="name" id="description"
                        maxlength="255" value=
                        @php
                            echo $role->name;
                        @endphp
                        />
                </div>

                <input type="number" name="id" hidden="true"
                maxlength="255" value=
                @php
                    echo $role->id;
                @endphp
                />




                <div class="form-group">
                    <label class="control-label label-marginb">Permisos</label>
                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="row">
                                    <table class="table table-condensed responsive">
                                        <tbody>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Ventas</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-orders-1" value=1
                                                                @php
                                                                    if (in_array(1, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-orders-1" value=2
                                                                @php
                                                                    if (in_array(2, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Ver</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Estadísticas de Ventas</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-saleStatistics-1" value=3
                                                                @php
                                                                    if (in_array(3, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Ver</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Cotizaciones</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-cotizaciones-1" value=4
                                                                @php
                                                                    if (in_array(4, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Ver</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Avisos</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-modals-1" value=5
                                                                @php
                                                                    if (in_array(5, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-modals-1" value=6
                                                                @php
                                                                    if (in_array(6, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-modals-1" value=7
                                                                @php
                                                                    if (in_array(7, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Slider Principal</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-sliders-1" value=8
                                                                @php
                                                                    if (in_array(8, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-sliders-1" value=9
                                                                @php
                                                                    if (in_array(9, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-sliders-1" value=10
                                                                @php
                                                                    if (in_array(10, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Slider Clientes</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-slidersClients-1" value=11
                                                                @php
                                                                    if (in_array(11, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-slidersClients-1" value=12
                                                                @php
                                                                    if (in_array(12, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-slidersClients-1" value=13
                                                                @php
                                                                    if (in_array(13, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Representaciones</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-slidersPartners-1"
                                                                value=14
                                                                @php
                                                                    if (in_array(14, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-slidersPartners-1"
                                                                value=15
                                                                @php
                                                                    if (in_array(15, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-slidersPartners-1"
                                                                value=16
                                                                @php
                                                                    if (in_array(16, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Mini Banner Home</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-publicities-1" value=17
                                                                @php
                                                                    if (in_array(17, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-publicities-1" value=18
                                                                @php
                                                                    if (in_array(18, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-publicities-1" value=19
                                                                @php
                                                                    if (in_array(19, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Páginas</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-pages-1" value=20
                                                                @php
                                                                    if (in_array(20, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-pages-1" value=21
                                                                @php
                                                                    if (in_array(21, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-pages-1" value=22
                                                                @php
                                                                    if (in_array(22, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Respuestas Correo</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-messages-1" value=23
                                                                @php
                                                                    if (in_array(23, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-messages-1" value=24
                                                                @php
                                                                    if (in_array(24, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-messages-1" value=25
                                                                @php
                                                                    if (in_array(25, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Respuestas Páginas</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-responses-1" value=26
                                                                @php
                                                                    if (in_array(26, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-responses-1" value=27
                                                                @php
                                                                    if (in_array(27, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-responses-1" value=28
                                                                @php
                                                                    if (in_array(28, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Sucursales</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-offices-1" value=29
                                                                @php
                                                                    if (in_array(29, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-offices-1" value=30
                                                                @php
                                                                    if (in_array(30, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-offices-1" value=31
                                                                @php
                                                                    if (in_array(31, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Clientes</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-clients-1" value=32
                                                                @php
                                                                    if (in_array(32, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-clients-1" value=33
                                                                @php
                                                                    if (in_array(33, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-clients-1" value=34
                                                                @php
                                                                    if (in_array(34, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-clients-1" value=35
                                                                @php
                                                                    if (in_array(35, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Importar Clientes</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Clientes - Direcciones</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-clientsAddress-1" value=36
                                                                @php
                                                                    if (in_array(36, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-clientsAddress-1" value=37
                                                                @php
                                                                    if (in_array(37, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-clientsAddress-1" value=38
                                                                @php
                                                                    if (in_array(38, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Marcas</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-brands-1" value=39
                                                                @php
                                                                    if (in_array(39, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-brands-1" value=40
                                                                @php
                                                                    if (in_array(40, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-brands-1" value=41
                                                                @php
                                                                    if (in_array(41, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Categorías</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-categories-1" value=42
                                                                @php
                                                                    if (in_array(42, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-categories-1" value=43
                                                                @php
                                                                    if (in_array(43, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-categories-1" value=44
                                                                @php
                                                                    if (in_array(44, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Productos</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-products-1" value=45
                                                                @php
                                                                    if (in_array(45, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-products-1" value=46
                                                                @php
                                                                    if (in_array(46, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-products-1" value=47
                                                                @php
                                                                    if (in_array(47, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-products-1" value=48
                                                                @php
                                                                    if (in_array(48, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Importar Productos</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-products-1" value=49
                                                                @php
                                                                    if (in_array(49, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Importar Galería</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Productos Galerías</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-productsGalleries-1"
                                                                value=50
                                                                @php
                                                                    if (in_array(50, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-productsGalleries-1"
                                                                value=51
                                                                @php
                                                                    if (in_array(51, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-productsGalleries-1"
                                                                value=52
                                                                @php
                                                                    if (in_array(52, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Cupón de Descuento</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-discounts-1" value=53
                                                                @php
                                                                    if (in_array(53, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-discounts-1" value=54
                                                                @php
                                                                    if (in_array(54, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-discounts-1" value=55
                                                                @php
                                                                    if (in_array(55, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Regiones</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-regions-1" value=56
                                                                @php
                                                                    if (in_array(56, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-regions-1" value=57
                                                                @php
                                                                    if (in_array(57, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-regions-1" value=58
                                                                @php
                                                                    if (in_array(58, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Provincias</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-provinces-1" value=59
                                                                @php
                                                                    if (in_array(59, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-provinces-1" value=60
                                                                @php
                                                                    if (in_array(60, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-provinces-1" value=61
                                                                @php
                                                                    if (in_array(61, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Comunas</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-locations-1" value=62
                                                                @php
                                                                    if (in_array(62, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-locations-1" value=63
                                                                @php
                                                                    if (in_array(63, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-locations-1" value=64
                                                                @php
                                                                    if (in_array(64, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Empresas Despacho</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-couriers-1" value=65
                                                                @php
                                                                    if (in_array(65, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-couriers-1" value=66
                                                                @php
                                                                    if (in_array(66, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-couriers-1" value=67
                                                                @php
                                                                    if (in_array(67, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                                
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Contacto</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-contacts-1" value=68
                                                                @php
                                                                    if (in_array(68, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Ver</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Perfiles y Permisos</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-roles-1" value=69
                                                                @php
                                                                    if (in_array(69, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-roles-1" value=70
                                                                @php
                                                                    if (in_array(70, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-roles-1" value=71
                                                                @php
                                                                    if (in_array(71, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Usuarios</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-users-1" value=72
                                                                @php
                                                                    if (in_array(72, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Agregar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-users-1" value=73
                                                                @php
                                                                    if (in_array(73, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-users-1" value=74
                                                                @php
                                                                    if (in_array(74, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Eliminar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Parámetros Generales</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-configurations-1" value=75
                                                                @php
                                                                    if (in_array(75, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    Metadatos</td>
                                                <td>
                                                    <div class="checkbox-inline checkbox-replace color-green">
                                                        <label class="cb-wrapper"><input type="checkbox"
                                                                name="permissions[]" id="chk-metadata-1" value=76
                                                                @php
                                                                    if (in_array(76, $rolePermissions)) {
                                                                        echo "checked='checked'";
                                                                    }
                                                                @endphp>
                                                            <div class="checked"></div>
                                                        </label>
                                                        <label>Editar</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>





                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{ route('roles') }}" type="button" class="btn btn-primary"><i
                            class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                </div>

            </form>

        </div>

    </div>



    <br />
    <x-slot name="js">
        <script type="text/javascript">
            jQuery(document).ready(function($) {

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

            });

        </script>
    </x-slot>
</x-app-layoutt>

<x-app-layoutt>


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
        action="{{route('configurations.update')}}"
        enctype="multipart/form-data" class="custom-validate form-groups-bordered">

        @csrf
        <div class="row">
            <div class="col-md-12">

                <ul class="nav nav-tabs left-aligned">
                    <!-- available classes "bordered", "right-aligned" -->
                    <li class="active"><a href="#tab1" data-toggle="tab">
                            <span><i class="fa fa-text-height" aria-hidden="true"></i></span>
                            <span>Configuración General</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <span><i class="fa fa-sliders" aria-hidden="true"></i></span>
                            <span>Configuración Despacho y Pago</span>
                        </a>
                    </li>
                    <?php
                    /*?>
                    <li>
                        <a href="#tab3" data-toggle="tab">
                            <span><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                            <span>Configuración Webpay</span>
                        </a>
                    </li>

                    <li>
                        <a href="#tab4" data-toggle="tab">
                            <span><i class="fa fa-truck" aria-hidden="true"></i></span>
                            <span>Configuración Shipit</span>
                        </a>
                    </li>
                    <?php */
                    ?>
                    <li>
                        <a href="#tab5" data-toggle="tab">
                            <span><i class="fa fa-share-alt" aria-hidden="true"></i></span>
                            <span>Configuración Redes Sociales</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content tab-validate">
                    <div class="tab-pane active" id="tab1">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group main-group-category">
                                    <label class="control-label">¿Sitio en Mantención?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="site_offline" id="site_offline" value="1" <?php if ($configuration->site_offline == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group main-group-category">
                                    <label class="control-label">¿Activa IVA?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="active_tax" id="active_tax" value="1" <?php if ($configuration->active_tax == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Al habilitar indicará que sus productos se visualizaran
                                        +IVA y se calculará el ítem IVA en el carro de compras y/o cotización.</label>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group main-group-category">
                                    <label class="control-label">¿Activa Carro de Compras?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="active_cart" id="active_cart" value="1" <?php if ($configuration->active_cart == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Al desactivar el carro de compras sólo se visualizará las
                                        secciones de la empresa.</label>
                                    <div class="clearfix"></div>
                                </div>

                                @php /*
                                <div class="form-group main-group-category">
                                    <label class="control-label">¿Activa Cotización?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="active_cotizacion" id="active_cotizacion"
                                                value="1" <?php if
                                                ($configuration->active_cotizacion == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <label class="formNote">* Al desactivar el carro de cotización sólo se visualizará
                                        las secciones de la empresa.</label>
                                    <div class="clearfix"></div>
                                </div>
                                */ @endphp

                                <div class="form-group">
                                    <label class="control-label">Dirección 1</label>
                                    <input type="text" class="form-control required" name="address" id="address"
                                        maxlength="255"
                                        value="<?php echo $configuration->address; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Ciudad 1</label>
                                    <input type="text" class="form-control required" name="city" id="city"
                                        maxlength="100"
                                        value="<?php echo $configuration->city; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Dirección 2</label>
                                    <input type="text" class="form-control required" name="address_2" id="address_2"
                                        maxlength="255"
                                        value="<?php echo $configuration->address_2; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Ciudad 2</label>
                                    <input type="text" class="form-control required" name="city_2" id="city_2"
                                        maxlength="100"
                                        value="<?php echo $configuration->city_2; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Teléfono 1</label>
                                    <input type="text" class="form-control" name="phone1" id="phone1" maxlength="50"
                                        value="<?php echo $configuration->phone1; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Teléfono 2</label>
                                    <input type="text" class="form-control" name="phone2" id="phone2" maxlength="50"
                                        value="<?php echo $configuration->phone2; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Teléfono 3 - Whatsapp</label>
                                    <input type="text" class="form-control" name="phone3" id="phone3" maxlength="50"
                                        value="<?php echo $configuration->phone3; ?>" />
                                </div>

                                <?php
                                /*?>
                                <div class="form-group">
                                    <label class="control-label">Teléfono 4</label>
                                    <input type="text" class="form-control" name="phone4" id="phone4" maxlength="50"
                                        value="<?php echo $configuration->phone4; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Teléfono 3</label>
                                    <input type="text" class="form-control" name="fax" id="fax" maxlength="50"
                                        value="<?php echo $this->configuration->fax; ?>" />
                                </div>
                                <?php */
                                ?>

                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control required email" name="email" id="email"
                                        value="<?php echo $configuration->email; ?>" />
                                </div>

                                @php /*
                                <div class="form-group">
                                    <label class="control-label">Email 2</label>
                                    <input type="text" class="form-control email" name="email2" id="email2"
                                        value="<?php echo $configuration->email2; ?>" />
                                </div>
                                */ @endphp

                                <div class="form-group">
                                    <label class="control-label">Email contacto</label>
                                    <input type="text" class="form-control required tagsinput" name="contact_email"
                                        id="contact_email"
                                        value="<?php echo $configuration->contact_email; ?>" />
                                    <label class="formNote">* Para agregar múltiples casillas, presionar enter al
                                        finalizar de escribir email.</label>
                                </div>

                                <?php
                                /*?>
                                <div class="form-group">
                                    <label class="control-label">Email ventas</label>
                                    <input type="text" class="form-control required tagsinput" name="sale_email"
                                        id="sale_email"
                                        value="<?php echo $configuration->sale_email; ?>" />
                                    <label class="formNote">* Para agregar múltiples casillas, presionar enter al
                                        finalizar de escribir email.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Email cotizaciones</label>
                                    <input type="text" class="form-control required tagsinput" name="cotizacion_email"
                                        id="cotizacion_email"
                                        value="<?php echo $configuration->cotizacion_email; ?>" />
                                    <label class="formNote">* Para agregar múltiples casillas, presionar enter al
                                        finalizar de escribir email.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Email suscripciones</label>
                                    <input type="text" class="form-control required tagsinput" name="suscription_email"
                                        id="suscription_email"
                                        value="<?php echo $this->configuration->suscription_email; ?>" />
                                    <label class="formNote">* Para agregar múltiples casillas, presionar enter al
                                        finalizar de escribir email.</label>
                                </div>
                                <?php */
                                ?>

                                <div class="form-group">
                                    <label class="control-label">Mapa 1 - iFrame</label>
                                    <textarea class="form-control" name="map_1" id="map_1"
                                        rows="3"><?php echo $configuration->map_1; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Mapa 1 - Link</label>
                                    <textarea class="form-control" name="map_1_link" id="map_1_link"
                                        rows="3"><?php echo $configuration->map_1_link; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Mapa 2 - iFrame</label>
                                    <textarea class="form-control" name="map_2" id="map_2"
                                        rows="3"><?php echo $configuration->map_2; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Mapa 2 - Link</label>
                                    <textarea class="form-control" name="map_2_link" id="map_2_link"
                                        rows="3"><?php echo $configuration->map_2_link; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Horario</label>
                                    <textarea class="form-control ckeditor" name="horary"
                                        id="horary"><?php echo $configuration->horary; ?></textarea>
                                </div>

                            </div>

                        </div>

                    </div>

                    <style type="text/css">
                        .tab-pane .checkbox {
                            min-height: 24px;
                            padding-top: 7px;
                        }

                    </style>

                    <div class="tab-pane" id="tab2">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">¿Tipo Despacho a Domicilio?</label>
                                    <div class="col-md-12 no-padding">
                                        <div class="radio radio-replace color-primary">
                                            <input type="radio" id="radio1" name="shipping_type" value="PROPIO" <?php if ($configuration->shipping_type == 'PROPIO') {
                                            echo "checked='checked'";
                                            } ?>>
                                            <label>Propio</label>
                                        </div>
                                        <div class="clearfix"></div>
                                        <?php
                                        /*?>
                                        <div class="radio radio-replace color-primary">
                                            <input type="radio" id="radio2" name="shipping_type" value="SHIPIT" <?php if($this->configuration->shipping_type == 'SHIPIT'){
                                            echo "checked='checked'"; } ?>>
                                            <label>Shipit</label>
                                        </div>
                                        <?php */
                                        ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Activar Despachos?</label>
                                    <div class="col-md-12 no-padding">
                                        <div class="checkbox checkbox-replace color-primary">
                                            <input type="checkbox" id="chk-shipping-active" name="shipping_active"
                                                value="1" <?php if ($configuration->shipping_active
                                            == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                            <label>Despacho Domicilio</label>
                                        </div>

                                        <div class="checkbox checkbox-replace color-primary">
                                            <input type="checkbox" id="chk-shipping-office-active"
                                                name="office_shipping_active" value="1" <?php if
                                                ($configuration->office_shipping_active == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                            <label>Retiro Tienda</label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">¿Activar Pagos?</label>
                                    <div class="col-md-12 no-padding">
                                        <div class="checkbox checkbox-replace color-primary">
                                            <input type="checkbox" id="chk-transfer-active" name="transfer_active"
                                                value="1" <?php if ($configuration->transfer_active
                                            == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                            <label>Transferencia Bancaria</label>
                                        </div>

                                        <div class="checkbox checkbox-replace color-primary">
                                            <input type="checkbox" id="chk-webpay-active" name="webpay_active" value="1"
                                                <?php if ($configuration->webpay_active == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                            <label>Webpay</label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Mínimo compras</label>
                                    <input type="text" class="form-control number" name="minimun_sale" id="minimun_sale"
                                        value="<?php echo $configuration->minimun_sale; ?>" />
                                    <label class="formNote">* En el caso de no haber un mínimo de compra, deberá
                                        completar con 0.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Mínimo compras despacho gratis</label>
                                    <input type="text" class="form-control number" name="minimum_free_shipping"
                                        id="minimum_free_shipping"
                                        value="<?php echo $configuration->minimum_free_shipping; ?>" />
                                    <label class="formNote">* En el caso de no haber un mínimo de compra para despacho
                                        gratis, deberá completar con 0.</label>
                                </div>

                                <?php
                                /*?>
                                <div class="form-group">
                                    <label class="control-label">Mínimo descuento</label>
                                    <input type="text" class="form-control number" name="discount_minimum"
                                        id="discount_minimum"
                                        value="<?php echo $this->configuration->discount_minimum; ?>" />
                                    <label class="formNote">* En el caso de no haber un mínimo para acceder al
                                        descuento, deberá completar con 0.</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Porcentaje descuento %</label>
                                    <input type="text" class="form-control digits" name="discount_percentage"
                                        id="discount_percentage"
                                        value="<?php echo $this->configuration->discount_percentage; ?>" />
                                </div>
                                <?php */
                                ?>

                                <div class="form-group">
                                    <label class="control-label">Texto despacho domicilio</label>
                                    <textarea class="form-control ckeditor" name="shipping_text"
                                        id="shipping_text"><?php echo $configuration->shipping_text; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Texto despacho domicilio por pagar</label>
                                    <textarea class="form-control ckeditor" name="shipping_text_for_paying"
                                        id="shipping_text_for_paying"><?php echo $configuration->shipping_text_for_paying; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Texto retiro en tienda</label>
                                    <textarea class="form-control ckeditor" name="office_shipping_text"
                                        id="office_shipping_text"><?php echo $configuration->office_shipping_text; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Texto transferencia</label>
                                    <textarea class="form-control ckeditor" name="transfer_text"
                                        id="transfer_text"><?php echo $configuration->transfer_text; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Texto webpay</label>
                                    <textarea class="form-control ckeditor" name="webpay_text"
                                        id="webpay_text"><?php echo $configuration->webpay_text; ?></textarea>
                                </div>

                            </div>

                        </div>

                    </div>

                    @php /*
                    <div class="tab-pane" id="tab3">
                        <div class="panel panel-primary">
                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group main-group-category">
                                    <label class="control-label">¿Modo Producción?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="webpay_environment" id="webpay_environment"
                                                value="1" <?php if
                                                ($configuration->webpay_environment == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Nombre Comercio</label>
                                    <input type="text" class="form-control required" name="webpay_name_company"
                                        id="webpay_name_company"
                                        value="<?php echo $configuration->webpay_name_company; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Código Comercio</label>
                                    <input type="text" class="form-control required digits" name="webpay_code"
                                        id="webpay_code"
                                        value="<?php echo $configuration->webpay_code; ?>" />
                                    <label class="formNote">* Debe anteponer a su código comercio entregado por
                                        transbank los siguientes digitos "5970".</label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Llave Privada</label>
                                    <textarea class="form-control required" name="webpay_private_key"
                                        id="webpay_private_key"
                                        rows="3"><?php echo $configuration->webpay_private_key; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Certificado Publico</label>
                                    <textarea class="form-control required" name="webpay_public_cert"
                                        id="webpay_public_cert"
                                        rows="3"><?php echo $configuration->webpay_public_cert; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Certificado Transbank</label>
                                    <textarea class="form-control required" name="webpay_tbk_cert" id="webpay_tbk_cert"
                                        rows="3"><?php echo $configuration->webpay_tbk_cert; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Recargo %</label>
                                    <input type="text" class="form-control required digits" name="webpay_tax"
                                        id="webpay_tax"
                                        value="<?php echo $configuration->webpay_tax; ?>" />
                                    <label class="formNote">* En caso de requerir incluir un recargo por el uso de la
                                        pasarela de pago webpay.</label>
                                </div>

                            </div>
                        </div>
                    </div>
                    */ @endphp

                    <div class="tab-pane" id="tab4">
                        <div class="panel panel-primary">
                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group main-group-category">
                                    <label class="control-label">¿Modo Producción?</label>
                                    <div class="col-md-12 no-padding">
                                        <div id="label-switch" class="make-switch" data-on-label="SI"
                                            data-off-label="NO">
                                            <input type="checkbox" name="shipit_environment" id="shipit_environment"
                                                value="1" <?php if
                                                ($configuration->shipit_environment == 1) {
                                            echo "checked='checked'";
                                            } ?>>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control email" name="shipit_email" id="shipit_email"
                                        value="<?php echo $configuration->shipit_email; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Token</label>
                                    <input type="text" class="form-control" name="shipit_token" id="shipit_token"
                                        value="<?php echo $configuration->shipit_token; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Recargo %</label>
                                    <input type="text" class="form-control required digits" name="shipit_tax"
                                        id="shipit_tax"
                                        value="<?php echo $configuration->shipit_tax; ?>" />
                                    <label class="formNote">* En caso de requerir incluir un recargo o margen por
                                        diferencias en cálculo que pueda sufrir el tarificador.</label>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab5">

                        <div class="panel panel-primary">

                            <div class="panel-body color-gris-fondo form-groups-bordered">

                                <div class="form-group">
                                    <label class="control-label">Facebook</label>
                                    <input type="text" class="form-control url" name="social_facebook"
                                        id="social_facebook"
                                        value="<?php echo $configuration->social_facebook; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Instagram</label>
                                    <input type="text" class="form-control url" name="social_instagram"
                                        id="social_instagram"
                                        value="<?php echo $configuration->social_instagram; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Linkedin</label>
                                    <input type="text" class="form-control url" name="social_linkedin"
                                        id="social_linkedin"
                                        value="<?php echo $configuration->social_linkedin; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Twitter</label>
                                    <input type="text" class="form-control url" name="social_twitter"
                                        id="social_twitter"
                                        value="<?php echo $configuration->social_twitter; ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Youtube</label>
                                    <input type="text" class="form-control url" name="social_youtube"
                                        id="social_youtube"
                                        value="<?php echo $configuration->social_youtube; ?>" />
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>

                <div class="form-group">
                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <a href="{{route('configurations')}}"
                        type="button" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Cancelar y
                        Volver</a>
                    <input type="hidden" name="id"
                        value="<?php echo $configuration->id; ?>" />
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

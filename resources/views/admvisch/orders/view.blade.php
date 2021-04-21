<x-app-layoutt>
    @if (Session::has('error'))
    @if (Session::get('error') == 'success')
        <div class="alert alert-success"><strong>OK!</strong> Proceso realizado correctamente.</div>
    @endif
    @if (Session::get('error') == 'failure')
        <div class="alert alert-danger"><strong>ERROR!</strong> Se ha producido un error, favor vuelva a intentarlo.
            Si el error persiste favor comunicarse al administrador.</div>
    @endif
@endif

    <ol class="breadcrumb bc-2 hidden-print">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li>
            <?php echo $title; ?>
        </li>
        <li class="active">
            <strong>Detalle de <?php echo $title; ?></strong>
        </li>
    </ol>

    <br class="hidden-print" />

    <div class="invoice">

        <div class="row">
            <div class="col-sm-8 invoice-left">
                <h3>ORDEN DE COMPRA NO. #<?php echo $order->id; ?></h3>
                <span class="badge badge-success"><?php echo
                    Application\Helper::formatDateToCompleteDateUser($order->created_at, true); ?></span>
            </div>
            <div class="col-sm-4 invoice-right">

                <a href="javascript:window.print();" class="btn btn-success btn-icon icon-left hidden-print">
                    Imprimir Orden de Compra
                    <i class="entypo-doc-text"></i>
                </a>

            </div>
        </div>

        <hr class="margin" />

        <div class="row">

            <?php
            $payment_type = Application\Helper::getTypePayment($order->payment_type);
            $status_payment = Application\Helper::getStatusOC($order->payment_status);

            $shipping_type = Application\Helper::getTypeShipping($order->shipping_type);
            $status_shipping = Application\Helper::getStatusShipping($order->shipping_status);

            $type_document = Application\Helper::getTypeDocumentSII($order->type_document);

            $shipping_comment = str_replace('class="color-danger"', '', $order->shipping_comment);
            $shipping_comment = str_replace('
            <hr>', '<br>', $shipping_comment);
            $shipping_comment = str_replace('<p>', '', $shipping_comment);
                $shipping_comment = str_replace('</p>', '<br>', $shipping_comment);
            $shipping_comment = str_replace('.<br>', '. ', $shipping_comment);

            $shipping_free = strpos(strip_tags($shipping_comment), 'Costo de Despacho : GRATIS.') === false ? false :
            true;

            $shipping_status_name = $order->shipping_type == 1 ?
            Application\Helper::getStatusShipping($order->shipping_status) :
            Application\Helper::getStatusOfficeShipping($order->shipping_status);
            ?>

            <div class="col-sm-6">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle de OC</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="30%" class="col-gris">Método de Despacho:</td>
                        <td width="70%"><?php echo $shipping_type; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Estado de Despacho:</td>
                        <td><span
                                class="badge badge-<?php echo Application\Helper::getColorStatusShipping($order->shipping_status); ?>"><?php echo $shipping_status_name; ?></span></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Método de Pago:</td>
                        <td><?php echo $payment_type; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Estado de Pago:</td>
                        <td><span
                                class="badge badge-<?php echo Application\Helper::getColorStatusOC($order->payment_status); ?>"><?php echo Application\Helper::getStatusOC($order->payment_status); ?></span></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Tipo de Documento:</td>
                        <td><?php echo $type_document; ?></td>
                    </tr>
                    <?php if (!empty($rder->offices_id)) { ?>
                    <tr>
                        <td width="30%" class="col-gris">Sucursal:</td>
                        <td width="70%"><?php echo $order->office->title . ' ' .
                            $order->office->description; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if (!empty($order->discount_code)) { ?>
                    <tr>
                        <td width="30%" class="col-gris">Cupón Utilizado:</td>
                        <td width="70%"><?php echo $order->discount_code; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="col-sm-6">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Cliente</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="30%" class="col-gris">Nombre / Razón Social:</td>
                        <td width="70%"><?php echo $order->business_name; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Rut:</td>
                        <td><?php echo $order->rut; ?></td>
                    </tr>
                    <?php if (!empty($order->commercial_business)) { ?>
                    <tr>
                        <td class="col-gris">Giro:</td>
                        <td><?php echo $order->commercial_business; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="col-gris">Email:</td>
                        <td><?php echo $order->email; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Teléfono:</td>
                        <td><?php echo $order->phone; ?></td>
                    </tr>
                </table>
            </div>

        </div>

        <?php if ($order->shipping_type == 1 || ($order->payment_type == 1 && $webpay)) {
        if ($webpay) {
        $tbk_fecha_transaccion = Application\Helper::dateFormatUser($webpay->tbk_fecha_transaccion);
        $tbk_hora_transaccion = $webpay->tbk_hora_transaccion;
        $tbk_orden_compra = $webpay->tbk_orden_compra;
        $tbk_numero_final_tarjeta = $webpay->tbk_numero_final_tarjeta;
        $tbk_codigo_autorizacion = $webpay->tbk_codigo_autorizacion;
        $tbk_numero_cuotas = $webpay->tbk_numero_cuotas;
        $tbk_codigo_tipo_pago = $webpay->tbk_codigo_tipo_pago;
        $tbk_monto = intval($webpay->tbk_monto);
        $tbk_tipo_transaccion = 'Venta';
        $tbk_tipo_moneda = 'Pesos Chilenos';

        if ($tbk_codigo_tipo_pago == 'VD') {
        $tbk_tipo_pago = 'Débito';
        } else {
        $tbk_tipo_pago = 'Crédito';
        }

        if ($tbk_codigo_tipo_pago == 'VD' && $tbk_numero_cuotas == 0) {
        $tbk_tipo_cuotas = 'Venta Débito';
        }
        if ($tbk_codigo_tipo_pago == 'VN' && $tbk_numero_cuotas == 0) {
        $tbk_tipo_cuotas = 'Sin Cuotas';
        }
        if ($tbk_codigo_tipo_pago == 'VC' && $tbk_numero_cuotas >= 4 && $tbk_numero_cuotas <= 48) {
            $tbk_tipo_cuotas='Cuotas Normales' ; } if ($tbk_codigo_tipo_pago=='SI' && $tbk_numero_cuotas==3) {
            $tbk_tipo_cuotas='Sin Interés' ; } if ($tbk_codigo_tipo_pago=='S2' && $tbk_numero_cuotas==2) {
            $tbk_tipo_cuotas='Sin Interés' ; } if ($tbk_codigo_tipo_pago=='NC' ) { $tbk_tipo_cuotas='Sin Interés' ; } }
            ?> <div class="margin">
    </div>

    <div class="row">

        <div class="col-sm-6">
            <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Despacho</h4>
            <table class="table table-bordered table-condensed">
                <tr>
                    <td width="30%" class="col-gris">Dirección:</td>
                    <td width="70%"><?php echo $order->address; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Numeración de Calle:</td>
                    <td><?php echo $order->address_number; ?></td>
                </tr>
                <?php if (!empty($order->office_number)) { ?>
                <tr>
                    <td class="col-gris">Departamento / Oficina:</td>
                    <td><?php echo $order->office_number; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="col-gris">Región:</td>
                    <td><?php echo $order->region_name; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Provincia:</td>
                    <td><?php echo $order->province_name; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Comuna / Localidad:</td>
                    <td><?php echo $order->location_name; ?></td>
                </tr>
            </table>
        </div>

        <?php if ($webpay) { ?>
        <div class="col-sm-6">
            <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Transacción</h4>
            <table class="table table-bordered table-condensed">
                <tr>
                    <td width="30%" class="col-gris">Fecha de Transacción:</td>
                    <td width="70%"><?php echo $tbk_fecha_transaccion . ' ' . $tbk_hora_transaccion; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Tarjeta Bancaria:</td>
                    <td>**************<?php echo $tbk_numero_final_tarjeta; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Código de Autorización:</td>
                    <td><?php echo $tbk_codigo_autorizacion; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Tipo de Transacción:</td>
                    <td><?php echo $tbk_tipo_transaccion; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Tipo de Pago:</td>
                    <td><?php echo $tbk_tipo_pago; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Número de Cuotas:</td>
                    <td><?php echo $tbk_numero_cuotas; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Tipo de Cuotas:</td>
                    <td><?php echo $tbk_tipo_cuotas; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Tipo de Moneda:</td>
                    <td><?php echo $tbk_tipo_moneda; ?></td>
                </tr>
            </table>
        </div>
        <?php } ?>

    </div>
    <?php
    } ?>

    <?php if ($order->type_document == 2) { ?>
    <div class="margin"></div>

    <div class="row">

        <div class="col-sm-6">
            <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Facturación</h4>
            <table class="table table-bordered table-condensed">
                <tr>
                    <td width="30%" class="col-gris">Razón Social:</td>
                    <td width="70%"><?php echo $rder->document_business_name; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Rut:</td>
                    <td><?php echo $order->document_rut; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Giro:</td>
                    <td><?php echo $order->document_commercial_business; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Teléfono:</td>
                    <td><?php echo $order->document_phone; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Dirección:</td>
                    <td><?php echo $order->document_address; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Numeración de Calle:</td>
                    <td><?php echo $order->document_address_number; ?></td>
                </tr>
                <?php if (!empty($order->document_office_number)) { ?>
                <tr>
                    <td class="col-gris">Departamento / Oficina:</td>
                    <td><?php echo $order->document_office_number; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="col-gris">Región:</td>
                    <td><?php echo $order->document_region_name; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Provincia:</td>
                    <td><?php echo $order->document_province_name; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Comuna / Localidad:</td>
                    <td><?php echo $order->document_location_name; ?></td>
                </tr>
            </table>
        </div>

    </div>
    <?php } ?>

    <div class="margin"></div>

    <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle de Productos</h4>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <td width="65%">Producto</td>
                <td width="10%">Código</td>
                <td width="5%">Cantidad</td>
                <td width="10%">Precio Unitario</td>
                <td width="10%">Precio Total</td>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0) {
            foreach ($products as $product) { ?>
            <tr>
                <td><?php echo $product->description; ?></td>
                <td><?php echo $product->code; ?></td>
                <td class="text-right"><?php echo Application\Helper::formatDecimals($product->quantity,
                    0); ?></td>
                <td class="text-right">$ <?php echo
                    Application\Helper::formatDecimals($product->unit_price, 0); ?></td>
                <td class="text-right">$ <?php echo
                    Application\Helper::formatDecimals($product->total_price, 0); ?></td>
            </tr>
            <?php }
            } ?>
        </tbody>
    </table>

    <div class="margin"></div>

    <div class="row">

        <div class="col-sm-12">

            <div class="invoice-right">

                <ul class="list-unstyled">
                    <li>
                        Subtotal:
                        <strong>$ <?php echo Application\Helper::formatDecimals($order->subtotal, 0); ?></strong>
                    </li>
                    <?php if ($order->discount > 0) { ?>
                    <li>
                        Descuento:
                        <strong>$ <?php echo Application\Helper::formatDecimals($order->discount, 0); ?></strong>
                    </li>
                    <?php } ?>
                    <?php if ($configuration->active_tax == 1) { ?>
                    <li>
                        IVA:
                        <strong>$ <?php echo Application\Helper::formatDecimals($order->tax, 0); ?></strong>
                    </li>
                    <?php } ?>
                    <?php if ($order->shipping_type == 2 || ($shipping_free == true || $order->shipping >
                    0)) { ?>
                    <li>
                        Despacho:
                        <strong>$ <?php echo Application\Helper::formatDecimals($order->shipping, 0); ?></strong>
                    </li>
                    <?php } ?>
                    <li>
                        Recargo:
                        <strong>$ <?php echo Application\Helper::formatDecimals($order->extra, 0); ?></strong>
                    </li>
                    <li>
                        Total:
                        <strong>$ <?php echo Application\Helper::formatDecimals($order->total, 0); ?></strong>
                    </li>
                </ul>

                <hr />

            </div>

        </div>

    </div>

    <form role="form" id="form1" method="post" action="{{ route('orders.update') }}" enctype="multipart/form-data"
        class="custom-validate form-groups-bordered">

        @csrf
        <div class="row hidden-print">

            <?php if ($configuration->shipping_type == 'SHIPIT') { ?>
            <div class="col-md-12">
                <div class="alert alert-danger"><strong>Aviso</strong> al cambiar el estado de la OC a compra aprobada
                    (estado de despacho), automáticamente se procederá a generar la Orden de Transporte y su etiqueta
                    correspondiente.</div>
            </div>
            <?php } ?>

            <div class="col-md-6 col-xs-12">

                <div class="panel panel-primary" data-collapsed="0">

                    <div class="panel-heading container-yellow">
                        <div class="panel-title">
                            1. Estado de Pago
                        </div>
                    </div>

                    <div class="panel-body color-gris-fondo" style="display: block;">

                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control required" name="payment_status" id="payment_status">
                                    <?php foreach (\Application\Helper::getDataStatusOC() as $keyStatus
                                    => $status) {
                                    $selected = $order->payment_status == $keyStatus ? 'selected="selected"' : ''; ?>
                                    <option value="<?php echo $keyStatus; ?>" <?php echo $selected; ?>><?php echo
                                    $status; ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-xs-12">

                <div class="panel panel-primary panel-shipping" data-collapsed="0">

                    <div class="panel-heading container-yellow">
                        <div class="panel-title">
                            2. Estado de Despacho
                        </div>
                    </div>

                    <div class="panel-body color-gris-fondo" style="display: block;">

                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="shipping_status" id="shipping_status">
                                    <?php foreach
                                    (\Application\Helper::getDataStatusShippingAndOfficeShipping() as $keyStatus =>
                                    $status) {
                                    $selected = $order->shipping_status == $keyStatus ? 'selected="selected"' : ''; ?>
                                    <option value="<?php echo $keyStatus; ?>" <?php echo $selected;?>><?php echo
                                    $status; ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?php if ($configuration->shipping_type == 'PROPIO') { ?>
        <div class="row hidden-print">

            <div class="col-md-6 col-xs-12">

                <div class="panel panel-primary panel-shipping-add" data-collapsed="0">

                    <div class="panel-heading container-yellow">
                        <div class="panel-title">
                            2.1. Seguimiento de Envío
                        </div>
                    </div>

                    <div class="panel-body color-gris-fondo" style="display: block;">

                        <p style="font-size:85%;">Empresa de Envío</p>
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="couriers_id" id="couriers_id">
                                    <option value="">- Seleccionar -</option>
                                    <?php if (count($ouriers) > 0) {
                                    foreach ($couriers as $courier) {
                                    $selected = isset($shipping) && $courier->id == $shipping->couriers_id ?
                                    'selected="selected"' : ''; ?>
                                    <option value="<?php echo $courier->id; ?>" <?php echo $selected;?>><?php echo
                                    $courier->title; ?></option>
                                    <?php
                                    }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <br>

                        <p style="font-size:85%;">Número de Envío</p>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="courier_number" id="courier_number"
                                    value="<?php echo $shipping_count > 0 ? $shipping->number : ''; ?>">
                            </div>
                        </div>

                        <br>

                        <p style="font-size:85%;">Enlace de Seguimiento</p>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="courier_link" id="courier_link"
                                    value="<?php echo $shipping_count > 0 ? $shipping->link : ''; ?>">
                            </div>
                        </div>

                        <br>

                        <p style="font-size:85%;">Observaciones</p>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control no-resize" rows="5" name="courier_message"
                                    id="courier_message"><?php echo $shipping_count > 0 ? $shipping->message : ''; ?></textarea>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        <?php } ?>

        <div class="row hidden-print">

            <div class="col-sm-12">

                <div class="invoice-left">

                    <button type="submit" class="btn btn-blue"><i class="fa fa-save"></i> Guardar</button>
                    <input type="hidden" name="id" value="<?php echo $order->id; ?>" />
                    <input type="hidden" name="shipping_type"
                        value="<?php echo $order->shipping_type; ?>" />

                </div>

            </div>

        </div>

    </form>

    </div>

    <x-slot name="js">

        <script type="text/javascript">
            jQuery(document).ready(function($) {

                var payment_status = $('select[name=payment_status]').find(':selected').val();
                if (payment_status == 2 || payment_status == 3) {
                    $('.panel-shipping').css('display', 'block');
                } else {
                    $('.panel-shipping').css('display', 'none');
                }

                $('select[name=payment_status]').change(function() {
                    var payment_status = $(this).find(':selected').val();

                    if (payment_status == 2 || payment_status == 3) {
                        $('.panel-shipping').css('display', 'block');
                    } else {
                        $('.panel-shipping').css('display', 'none');
                    }
                });

                //-----------------------------------------------------------------------------

                var shipping_type = $('input[name=shipping_type]').val();
                var shipping_status = $('select[name=shipping_status]').find(':selected').val();
                if (shipping_type == 1) {
                    if (shipping_status == 4 || shipping_status == 5) {
                        $('.panel-shipping-add').css('display', 'block');
                    } else {
                        $('.panel-shipping-add').css('display', 'none');
                    }
                } else {
                    $('.panel-shipping-add').css('display', 'none');
                }

                $('select[name=shipping_status]').change(function() {
                    var shipping_status = $(this).find(':selected').val();
                    var shipping_type = $('input[name=shipping_type]').val();

                    if (shipping_type == 1) {
                        if (shipping_status == 4 || shipping_status == 5) {
                            $('.panel-shipping-add').css('display', 'block');
                        } else {
                            $('.panel-shipping-add').css('display', 'none');
                        }
                    } else {
                        $('.panel-shipping-add').css('display', 'none');
                    }
                });

            });

        </script>
    </x-slot>
</x-app-layoutt>

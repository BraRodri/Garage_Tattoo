<x-app-layoutt>


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
                <h3>COTIZACIÓN NO. #<?php echo $order->id; ?></h3>
                <span class="badge badge-success"><?php echo
                    Application\Helper::formatDateToCompleteDateUser($order->created_at, true); ?></span>
            </div>
            <div class="col-sm-4 invoice-right">
                <a href="javascript:window.print();" class="btn btn-success btn-icon icon-left hidden-print">
                    Imprimir Cotización
                    <i class="entypo-doc-text"></i>
                </a>
            </div>
        </div>

        <hr class="margin" />

        <div class="row">

            <div class="col-sm-6">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Cliente</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="30%" class="col-gris">Sucursal:</td>
                        <td width="70%"><?php echo $order->office['title'] . ' ' .
                            $order->office['description']; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Razón Social:</td>
                        <td><?php echo $order->business_name; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Rut:</td>
                        <td><?php echo $order->rut; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Email:</td>
                        <td><?php echo $order->email; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Teléfono:</td>
                        <td><?php echo $order->phone; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Dirección:</td>
                        <td><?php echo $order->address_full; ?></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Observaciones:</td>
                        <td><?php echo $order->order_comment; ?></td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="margin"></div>

        <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle de Productos</h4>
        <table class="table table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="65%">Producto</th>
                    <th width="10%">Código</th>
                    <th width="5%">Cantidad</th>
                    <th width="10%">Precio Unitario</th>
                    <th width="10%">Precio Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0) {
                foreach ($products as $product) { ?>
                <tr>
                    <td><?php echo $product->description; ?></td>
                    <td><?php echo $product->code; ?></td>
                    <td class="text-right"><?php echo
                        Application\Helper::formatDecimals($product->quantity, 0); ?></td>
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
                            Total:
                            <strong>$ <?php echo Application\Helper::formatDecimals($order->total,
                                0); ?></strong>
                        </li>
                    </ul>

                    <hr />

                </div>

            </div>

        </div>

    </div>

    <x-slot name="js">

        <script type="text/javascript">
            jQuery(document).ready(function($) {

            });

        </script>
    </x-slot>
</x-app-layoutt>

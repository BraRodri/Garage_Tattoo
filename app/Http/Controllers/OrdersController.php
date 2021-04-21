<?php

namespace App\Http\Controllers;


use App\Exports\OrdersExport;
use App\Models\Configurations;
use App\Models\Couriers;
use App\Models\Metadata;
use App\Models\Office;
use App\Models\Orders;
use App\Models\OrdersCouriers;
use App\Models\OrdersDetails;
use App\Models\Pages;
use App\Models\Webpay;
use Illuminate\Http\Request;
use Application\Helper;
use Application\HelperExcel;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use Libraries\Shipit\ShipitCustom;
use PHPExcel;
use Symfony\Component\Console\Output\ConsoleOutput;


class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private $title = 'Ordenes de Compras';
    private $module = 'orders';



    public function index()
    {


        $_status_payment = $_status_shipping = $_offices = array();

        // --------------------- FORM POST

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document'])) ? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document'])) ? Helper::postValue('date_end_document') : date('d-m-Y');


        if (isset($_POST['status_payment']) && !empty($_POST['status_payment'])) {
            if (is_array($_POST['status_payment'])) {
                foreach ($_POST['status_payment'] as $status_payment) {
                    $_status_payment[] = $status_payment;
                }
            } else {
                $_status_payment[] = $_POST['status_payment'];
            }
        }

        if (isset($_POST['status_shipping']) && !empty($_POST['status_shipping'])) {
            if (is_array($_POST['status_shipping'])) {
                foreach ($_POST['status_shipping'] as $status_shipping) {
                    $_status_shipping[] = $_POST['status_shipping'];
                }
            } else {
                $_status_shipping[] = $_POST['status_shipping'];
            }
        }

        if (isset($_POST['offices']) && !empty($_POST['offices'])) {
            if (is_array($_POST['offices'])) {
                foreach ($_POST['offices'] as $office) {
                    $_offices[] = $office;
                }
            } else {
                $_offices[] = $_POST['offices'];
            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $status_payment = Helper::getDataStatusOC();
  

        $status_shipping = Helper::getDataStatusShippingAndOfficeShipping();
      

        $offices = Office::where(['active' => 1])->orderBy('position')->get();
      

 

        return view('admvisch.orders.index')->with(['status_payment'=>$status_payment , 'status_shipping'=>$status_shipping, 'offices'=>$offices, '_date_start_document'=>$_date_start_document ,
         '_date_end_document'=>$_date_end_document , '_status_payment'=>$_status_payment, '_status_shipping'=>$_status_shipping, '_offices' => $_offices, 'error' => '' ,'title' => $this->title,'module' => $this->module  ]);
    }

    public function documents()
    {

        $table_documents_body = array();
        $where = "";

        // --------------------- FORM POST

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document'])) ? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document'])) ? Helper::postValue('date_end_document') : date('d-m-Y');

        $_status_payment = $_status_shipping = $_offices = '';

        if (isset($_POST['status_payment']) && !empty($_POST['status_payment'])) {
            $_status_payment_array = explode('^', substr($_POST['status_payment'], 0, -1));
            /*
            if (count($_status_payment_array) > 0) {
                foreach ($_status_payment_array AS $status_payment) {
                    $_status_payment .= "'" . $status_payment . "',";
                }
                $_status_payment = substr($_status_payment, 0, -1);
            }
            */
            $_status_payment = $_status_payment_array;
        }

        if (isset($_POST['status_shipping']) && !empty($_POST['status_shipping'])) {
            $_status_shipping_array = explode('^', substr($_POST['status_shipping'], 0, -1));
            if (count($_status_shipping_array) > 0) {
                foreach ($_status_shipping_array as $status_shipping) {
                    $_status_shipping .= "'" . $status_shipping . "',";
                }
                $_status_shipping = substr($_status_shipping, 0, -1);
            }
        }

        if (isset($_POST['offices']) && !empty($_POST['offices'])) {
            $_offices_array = explode('^', substr($_POST['offices'], 0, -1));
            if (count($_offices_array) > 0) {
                foreach ($_offices_array as $office) {
                    $_offices .= "'" . $office . "',";
                }
                $_offices = substr($_offices, 0, -1);
            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $documents = Orders::join('offices', 'orders.offices_id', '=', 'offices.id', 'LEFT OUTER')
            ->select([
                'orders.id',
                'orders.created_at',
                'orders.rut',
                'orders.business_name',
                'orders.payment_type',
                'orders.payment_status',
                'orders.shipping_type',
                'orders.shipping_status',
                'orders.total',
                'offices.title AS office_title',
                'offices.description AS office_description'
            ])
            ->whereNotNull('orders.id');

        if (!empty($_date_start_document)) {
            $documents = $documents->whereRaw("DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if (!empty($_date_end_document)) {
            $documents = $documents->whereRaw("DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        if (!empty($_status_payment)) {
            $documents = $documents->whereIn("orders.payment_status", $_status_payment);
        }

        if (!empty($_status_shipping)) {
            $documents = $documents->whereIn("orders.shipping_status", $_status_shipping);
        }

        if (!empty($_offices)) {
            $documents = $documents->whereIn("orders.offices_id", $_offices);
        }

        $documents = $documents->orderBy('orders.id', 'DESC')->get();

        if ($documents->count() > 0) {
            foreach ($documents as $document) {

                $shipping_status_name = ($document->shipping_type == 1) ? Helper::getStatusShipping($document->shipping_status) : Helper::getStatusOfficeShipping($document->shipping_status);

                $table_documents_body[] = array(
                    '<input type="checkbox" name="documents[]" value="' . $document->id . '">',
                    '<a style="cursor:pointer" class="show-compact-view" id="' . $document->id . '">Detalle Compacto</a>',
                    '<a href="' . route('orders.view',$document->id) .'" target="_blank"><i class="fa fa-list-ol" aria-hidden="true"></i> Ver Detalle</a>',
                    $document->id,
                    Helper::dateFormatUser($document->created_at),
                    $document->office_title . ' ' . $document->office_description,
                    $document->rut,
                    $document->business_name,
                    Helper::getTypePayment($document->payment_type),
                    '<span class="badge badge-' . Helper::getColorStatusOC($document->payment_status) . '">' . Helper::getStatusOC($document->payment_status) . '</span>',
                    Helper::getTypeShipping($document->shipping_type),
                    '<span class="badge badge-' . Helper::getColorStatusShipping($document->shipping_status) . '">' . $shipping_status_name . '</span>',
                    '$ ' . Helper::formatDecimals($document->total, 0)
                );
            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $total_ventas = $num_pendientes = $num_aprobadas = $num_rechazadas = 0;

        //--------------------------------------------------------------------------------------------------------------------------------------

        $documents2 = Orders::whereNotNull('orders.id');

        if (!empty($_date_start_document)) {
            $documents2 = $documents2->whereRaw("DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if (!empty($_date_end_document)) {
            $documents2 = $documents2->whereRaw("DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        if (!empty($_status_payment)) {
            $documents2 = $documents2->whereIn("orders.payment_status", $_status_payment);
        }

        if (!empty($_status_shipping)) {
            $documents2 = $documents2->whereIn("orders.shipping_status", $_status_shipping);
        }

        if (!empty($_offices)) {
            $documents2 = $documents2->whereIn("orders.offices_id", $_offices);
        }

        $documents2 = $documents2->whereNotIn('orders.payment_status', [4])->sum('total');
        if ($documents2) {
            $total_ventas = Helper::formatDecimals($documents2, 0);
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $documents3 = Orders::whereNotNull('orders.id');

        if (!empty($_date_start_document)) {
            $documents3 = $documents3->whereRaw("DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if (!empty($_date_end_document)) {
            $documents3 = $documents3->whereRaw("DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        if (!empty($_status_payment)) {
            $documents3 = $documents3->whereIn("orders.payment_status", $_status_payment);
        }

        if (!empty($_status_shipping)) {
            $documents3 = $documents3->whereIn("orders.shipping_status", $_status_shipping);
        }

        if (!empty($_offices)) {
            $documents3 = $documents3->whereIn("orders.offices_id", $_offices);
        }

        $documents3 = $documents3->whereIn('orders.payment_status', [1])->count();
        if ($documents3) {
            $num_pendientes = Helper::formatDecimals($documents3, 0);
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $documents4 = Orders::whereNotNull('orders.id');

        if (!empty($_date_start_document)) {
            $documents4 = $documents4->whereRaw("DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if (!empty($_date_end_document)) {
            $documents4 = $documents4->whereRaw("DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        if (!empty($_status_payment)) {
            $documents4 = $documents4->whereIn("orders.payment_status", $_status_payment);
        }

        if (!empty($_status_shipping)) {
            $documents4 = $documents4->whereIn("orders.shipping_status", $_status_shipping);
        }

        if (!empty($_offices)) {
            $documents4 = $documents4->whereIn("orders.offices_id", $_offices);
        }

        $documents4 = $documents4->whereIn('orders.payment_status', [2, 3])->count();
        if ($documents4) {
            $num_aprobadas = Helper::formatDecimals($documents4, 0);
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $documents5 = Orders::whereNotNull('orders.id');

        if (!empty($_date_start_document)) {
            $documents5 = $documents5->whereRaw("DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if (!empty($_date_end_document)) {
            $documents5 = $documents5->whereRaw("DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        if (!empty($_status_payment)) {
            $documents5 = $documents5->whereIn("orders.payment_status", $_status_payment);
        }

        if (!empty($_status_shipping)) {
            $documents5 = $documents5->whereIn("orders.shipping_status", $_status_shipping);
        }

        if (!empty($_offices)) {
            $documents5 = $documents5->whereIn("orders.offices_id", $_offices);
        }

        $documents5 = $documents5->whereIn('orders.payment_status', [4])->count();
        if ($documents5) {
            $num_rechazadas = Helper::formatDecimals($documents5, 0);
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        echo json_encode(array(
            'data' => $table_documents_body,
            'total_ventas' => $total_ventas,
            'num_pendientes' => $num_pendientes,
            'num_aprobadas' => $num_aprobadas,
            'num_rechazadas' => $num_rechazadas,
        ));
    }

    public function view($id)
    {

        $order = Orders::findOrFail($id);
        $products = OrdersDetails::where(['orders_id' => $id])->get();
        $shipping = OrdersCouriers::where(['orders_id' => $id])->first();
        $shipping_count = ($shipping) ? $shipping->count() : 0;

        $couriers = Couriers::where(['active' => 1])->orderBy('title')->get();

        $webpay = array();
        if ($order->payment_type == 1) {
            $webpay = Webpay::where(['tbk_orden_compra' => $order->id])->first();
        }

        $configuration = Configurations::where(['id' => 1])->orderBy('id', 'desc')->first();


        return view('admvisch.orders.view')->with(['title'=>$this->title, 'module'=>$this->module, 'order'=>$order, 'products'=>$products, 
        'shipping'=>$shipping, 'shipping_count'=>$shipping_count, 'couriers'=>$couriers, 'webpay'=>$webpay, 'configuration'=>$configuration]);
    }

    public function viewCompact()
    {

        $id = Helper::postValue('id');

        $order = Orders::findOrFail($id);
        $products = OrdersDetails::where(['orders_id' => $id])->get();
        $configuration = Configurations::where(['id' => 1])->orderBy('id', 'desc')->first();

        $address_name = '';

        $address_name  = $order->address;
        $address_name .= " N° " . $order->address_number;
        $address_name .= (!empty($order->office_number)) ? ", Depto / Oficina " . $order->office_number : '';
        $address_name .= ", " . $order->region_name;
        $address_name .= ", " . $order->province_name;
        $address_name .= ", " . $order->location_name;

        $payment_type = Helper::getTypePayment($order->payment_type);
        $status_payment = Helper::getStatusOC($order->payment_status);

        $shipping_type = Helper::getTypeShipping($order->shipping_type);
        $status_shipping = Helper::getStatusShipping($order->shipping_status);

        $type_document = Helper::getTypeDocumentSII($order->type_document);

        $shipping_comment = str_replace('class="color-danger"', '', $order->shipping_comment);
        $shipping_comment = str_replace('<hr>', '<br>', $shipping_comment);
        $shipping_comment = str_replace('<p>', '', $shipping_comment);
        $shipping_comment = str_replace('</p>', '<br>', $shipping_comment);
        $shipping_comment = str_replace('.<br>', '. ', $shipping_comment);

        $shipping_free = (strpos(strip_tags($shipping_comment), 'Costo de Despacho : GRATIS.') === false) ? false : true;

        $shipping_status_name = ($order->shipping_type == 1) ? Helper::getStatusShipping($order->shipping_status) : Helper::getStatusOfficeShipping($order->shipping_status);

        $content_product = '';
        if (count($products) > 0) {
            foreach ($products as $product) {
                $content_product .= '
                <tr>
                    <td>' . $product->description . '</td>
                    <td>' . $product->code . '</td>
                    <td class="text-right">' . Helper::formatDecimals($product->quantity, 0) . '</td>
                    <td class="text-right">$ ' . Helper::formatDecimals($product->unit_price, 0) . '</td>
                    <td class="text-right">$ ' . Helper::formatDecimals($product->total_price, 0) . '</td>
                </tr>
                ';
            }
        }

        $content_subtotales = '';

        $content_subtotales .= '
        <tr>
            <td colspan="4" align="right"><strong>Subtotal</strong></td>
            <td align="right"><strong>$ ' . Helper::formatDecimals($order->subtotal, 0) . '</strong></td>
        </tr>
        ';

        if ($order->discount > 0) {
            $content_subtotales .= '
            <tr>
                <td colspan="4" align="right"><strong>Descuento</strong></td>
                <td align="right"><strong>$ ' . Helper::formatDecimals($order->discount, 0) . '</strong></td>
            </tr>
            ';
        }

        if ($configuration->active_tax == 1) {
            $content_subtotales .= '
            <tr>
                <td colspan="4" align="right"><strong>IVA</strong></td>
                <td align="right"><strong>$ ' . Helper::formatDecimals($order->tax, 0) . '</strong></td>
            </tr>
            ';
        }

        if ($order->shipping_type == 2 || ($shipping_free == true || $order->shipping > 0)) {
            $content_subtotales .= '
            <tr>
                <td colspan="4" align="right"><strong>Despacho</strong></td>
                <td align="right"><strong>$ ' . Helper::formatDecimals($order->shipping, 0) . '</strong></td>
            </tr>
            ';
        }

        $content_subtotales .= '
        <tr>
            <td colspan="4" align="right"><strong>Recargo</strong></td>
            <td align="right"><strong>$ ' . Helper::formatDecimals($order->extra, 0) . '</strong></td>
        </tr>
        ';

        $content_subtotales .= '
        <tr>
            <td colspan="4" align="right"><strong>Total</strong></td>
            <td align="right"><strong>$ ' . Helper::formatDecimals($order->total, 0) . '</strong></td>
        </tr>
        ';

        $data = '
        <div class="row">
            <div class="col-sm-12 invoice-left">
                <h3>ORDEN DE COMPRA NO. #' . $order->id . '</h3>
                <span class="badge badge-success">' . Helper::formatDateToCompleteDateUser($order->created_at, true) . '</span>
            </div>
        </div>

        <hr class="margin" />

        <div class="row">

            <div class="col-sm-12">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle General</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="30%" class="col-gris">Método de Despacho:</td>
                        <td width="70%">' . $shipping_type . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Estado de Despacho:</td>
                        <td><span class="badge badge-' . Helper::getColorStatusShipping($order->shipping_status) . '">' . $shipping_status_name . '</span></td>
                    </tr>
                    <tr>
                        <td class="col-gris">Método de Pago:</td>
                        <td>' . $payment_type . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Estado de Pago:</td>
                        <td><span class="badge badge-' . Helper::getColorStatusOC($order->payment_status) . '">' . Helper::getStatusOC($order->payment_status) . '</span></td>
                    </tr>
                    ';

        if (!empty($order->offices_id)) {

            $data .= '
                    <tr>
                        <td class="col-gris">Sucursal:</td>
                        <td>' . $order->office->description . '</td>
                    </tr>';
        }

        if (!empty($order->discount_code)) {

            $data .= '
                    <tr>
                        <td class="col-gris">Cupón Utilizado:</td>
                        <td>' . $order->discount_code . '</td>
                    </tr>';
        }

        $data .= '
                </table>
            </div>

        </div>

        <div class="margin"></div>

        <div class="row">

            <div class="col-sm-6">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Cliente</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="30%" class="col-gris">Razón Social:</td>
                        <td width="70%">' . $order->business_name . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Rut:</td>
                        <td>' . $order->rut . '</td>
                    </tr>';

        if (!empty($order->commercial_business)) {

            $data .= '
                        <tr>
                            <td class="col-gris">Giro:</td>
                            <td>' . $order->commercial_business . '</td>
                        </tr>';
        }

        $data .= '   
                    <tr>
                        <td class="col-gris">Email:</td>
                        <td>' . $order->email . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Teléfono:</td>
                        <td>' . $order->phone . '</td>
                    </tr>
                </table>
            </div>
            ';

        if ($order->shipping_type == 1 && !empty($address_name)) {
            $data .= '
            <div class="col-sm-6">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Despacho</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="30%" class="col-gris">Dirección:</td>
                        <td width="70%">' . $address_name . '</td>
                    </tr>
                </table>
            </div>
            ';
        }

        $data .= '
        </div>

        <div class="margin"></div>

        <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle de Productos</h4>
        <table class="table table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th width="56%">Producto</th>
                <th width="10%">Código</th>
                <th width="10%">Cantidad</th>
                <th width="12%">Precio Unit.</th>
                <th width="12%">Precio Total</th>
            </tr>
            </thead>
            <tbody>
            ' . $content_product . '
            ' . $content_subtotales . '
            </tbody>
        </table>
        ';

        echo json_encode(array('data' => $data));
    }


    public function export($selected = false){

        $output = new ConsoleOutput();
        $output->writeln("<info>ENTRANDO AL EXPORT</info>");
        $where = "";

        $header = array();

        $header[] = "N°";
        $header[] = "Fecha";
        $header[] = "Sucursal";
        $header[] = "Nombre / Razón Social";
        $header[] = "Rut";
        $header[] = "Giro";
        $header[] = "Email";
        $header[] = "Teléfono";
        $header[] = "Forma Pago";
        $header[] = "Estado Pago";
        $header[] = "Despacho";
        $header[] = "Estado Despacho";
        $header[] = "Dirección";
        $header[] = "Numeración Calle";
        $header[] = "Número Departamento / Oficina";
        $header[] = "Región";
        $header[] = "Provincia";
        $header[] = "Comuna";
        $header[] = "Empresa de Transporte";
        $header[] = "N° de Seguimiento";
        $header[] = "Enlace de Seguimiento";
        $header[] = "Observaciones Transporte";
        $header[] = "Observaciones Pedido";
        $header[] = "Observaciones Despacho";
        $header[] = "Tipo Documento";
        $header[] = "Cupón Utilizado";
        $header[] = "Razón Social";
        $header[] = "Rut";
        $header[] = "Giro";
        $header[] = "Teléfono";
        $header[] = "Dirección";
        $header[] = "Numeración Calle";
        $header[] = "Número Departamento / Oficina";
        $header[] = "Región";
        $header[] = "Provincia";
        $header[] = "Comuna";
        $header[] = "Subtotal";
        $header[] = "Descuento";
        $header[] = "IVA";
        $header[] = "Despacho";
        $header[] = "Recargo";
        $header[] = "Total";
        $header[] = "Código";
        $header[] = "Variedad";
        $header[] = "Producto";
        $header[] = "Cantidad";
        $header[] = "Precio Unitario";
        $header[] = "Precio Total";

        $objPHPExcel = new PHPExcel;
        HelperExcel::applyBookProperties($objPHPExcel, $this->title);
        HelperExcel::applyZoom($objPHPExcel, 85);
        HelperExcel::applyAutoAdjust($objPHPExcel, 0, count($header));
        HelperExcel::applyFixedRow($objPHPExcel, 0, 2);

        $counter_row = 1;

        $column = 0;
        foreach ($header as $item) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $counter_row, $item);
            $column++;
        }

        HelperExcel::applyCellProperties($objPHPExcel, 0, count($header), $counter_row, true, "FFFFFF", 11, "Calibri", "1f497d", "000000");
        $counter_row++;

        //--------------------------------------------------------------------------------------------------------------------------------------

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document']))? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document']))? Helper::postValue('date_end_document') : date('d-m-Y');
        $_status_payment = $_status_shipping = $_offices = '';

        if(isset($_POST['status_payment']) && !empty($_POST['status_payment'])){
            if(is_array($_POST['status_payment'])) {
                $_status_payment = implode(',', $_POST['status_payment']);
            } else {
                $_status_payment = $_POST['status_payment'];
            }
        }

        if(isset($_POST['status_shipping']) && !empty($_POST['status_shipping'])){
            if(is_array($_POST['status_shipping'])) {
                $_status_shipping = implode(',', $_POST['status_shipping']);
            } else {
                $_status_shipping = $_POST['status_shipping'];
            }
        }

        if(isset($_POST['offices']) && !empty($_POST['offices'])){
            if(is_array($_POST['offices'])) {
                $_offices = implode(',', $_POST['offices']);
            } else {
                $_offices = $_POST['offices'];
            }
        }

        //-----------------------------------------------

        $documents = OrdersDetails::join('orders', 'orders_details.orders_id', '=', 'orders.id')
            ->join('orders_couriers', 'orders_couriers.orders_id', '=', 'orders.id', 'LEFT OUTER')
            ->join('couriers', 'orders_couriers.couriers_id', '=', 'couriers.id', 'LEFT OUTER')
            ->join('offices', 'orders.offices_id', '=', 'offices.id', 'LEFT OUTER')
            ->select([
                'orders.id',
                'orders.created_at',
                'orders.business_name',
                'orders.rut',
                'orders.commercial_business',
                'orders.email',
                'orders.phone',
                'orders.payment_type',
                'orders.payment_status',
                'orders.shipping_type',
                'orders.shipping_status',
                'orders.address',
                'orders.address_number',
                'orders.office_number',
                'orders.region_name',
                'orders.province_name',
                'orders.location_name',
                'couriers.title AS courier_name',
                'orders_couriers.number',
                'orders_couriers.link',
                'orders_couriers.message',
                'orders.order_comment',
                'orders.shipping_comment',
                'orders.type_document',
                'orders.discount_code',
                'orders.document_business_name',
                'orders.document_rut',
                'orders.document_commercial_business',
                'orders.document_phone',
                'orders.document_address',
                'orders.document_address_number',
                'orders.document_office_number',
                'orders.document_region_name',
                'orders.document_province_name',
                'orders.document_location_name',
                'orders.subtotal',
                'orders.discount',
                'orders.shipping',
                'orders.extra',
                'orders.tax',
                'orders.total',
                'orders_details.code',
                'orders_details.description',
                'orders_details.combination',
                'orders_details.quantity',
                'orders_details.unit_price',
                'orders_details.total_price',
                'offices.title AS office_title',
                'offices.description AS office_description'
            ])
            ->whereNotNull('orders.id');

        if(!empty($_date_start_document)){
            $documents = $documents->whereRaw("DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if(!empty($_date_end_document)){
            $documents = $documents->whereRaw("DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        if(!empty($_status_payment)){
            $documents = $documents->whereIn("orders.payment_status", $_status_payment);
        }

        if(!empty($_status_shipping)){
            $documents = $documents->whereIn("orders.shipping_status", $_status_shipping);
        }

        if(!empty($_offices)){
            $documents = $documents->whereIn("orders.offices_id", $_offices);
        }

        if($selected == 1){

            $_documents = '';

            if(isset($_POST['documents']) && !empty($_POST['documents'])){
                if(count($_POST['documents']) > 0){
                    $_documents = $_POST['documents'];
                    $documents = $documents->whereIn("orders.id", $_documents);
                }
            }
        }

        $documents = $documents->orderBy('orders.id', 'DESC')->get();

        if ($documents->count() > 0) {
            foreach ($documents AS $document) {
                $data = array();

                $data[] = $document->id;
                $data[] = Helper::dateFormatSystem(Helper::dateFormatUser($document->created_at, false));
                $data[] = $document->office_title . ' ' . $document->office_description;
                $data[] = $document->business_name;
                $data[] = $document->rut;
                $data[] = $document->commercial_business;
                $data[] = $document->email;
                $data[] = $document->phone;
                $data[] = Helper::getTypePayment($document->payment_type);
                $data[] = Helper::getStatusOC($document->payment_status);
                $data[] = Helper::getTypeShipping($document->shipping_type);
                $data[] = ($document->shipping_type == 1)? Helper::getStatusShipping($document->shipping_status) : Helper::getStatusOfficeShipping($document->shipping_status);
                $data[] = $document->address;
                $data[] = $document->address_number;
                $data[] = $document->office_number;
                $data[] = $document->region_name;
                $data[] = $document->province_name;
                $data[] = $document->location_name;
                $data[] = $document->courier_name;
                $data[] = $document->number;
                $data[] = $document->link;
                $data[] = $document->message;
                $data[] = $document->order_comment;
                $data[] = $document->shipping_comment;
                $data[] = Helper::getTypeDocumentSII($document->type_document);
                $data[] = $document->discount_code;
                $data[] = $document->document_business_name;
                $data[] = $document->document_rut;
                $data[] = $document->document_commercial_business;
                $data[] = $document->document_phone;
                $data[] = $document->document_address;
                $data[] = $document->document_address_number;
                $data[] = $document->document_office_number;
                $data[] = $document->document_region_name;
                $data[] = $document->document_province_name;
                $data[] = $document->document_location_name;
                $data[] = '$ ' . Helper::formatDecimals($document->subtotal, 0);
                $data[] = '$ ' . Helper::formatDecimals($document->discount, 0);
                $data[] = '$ ' . Helper::formatDecimals($document->tax, 0);
                $data[] = '$ ' . Helper::formatDecimals($document->shipping, 0);
                $data[] = '$ ' . Helper::formatDecimals($document->extra, 0);
                $data[] = '$ ' . Helper::formatDecimals($document->total, 0);
                $data[] = $document->code;
                $data[] = $document->combination;
                $data[] = $document->description;
                $data[] = $document->quantity;
                $data[] = '$ ' . Helper::formatDecimals($document->unit_price, 0);
                $data[] = '$ ' . Helper::formatDecimals($document->total_price, 0);

                $column = 0;
                foreach ($data as $item) {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($column, $counter_row, $item);
                    $column++;
                }

                $counter_row++;
            }
        }

       return HelperExcel::applyExcelOutput($objPHPExcel, "ordenes-compras");
    }


    public function update()
    {
        $id = Helper::postValue('id');
        $payment_status = Helper::postValue('payment_status');
        $shipping_status = Helper::postValue('shipping_status');
        $couriers_id = Helper::postValue('couriers_id');
        $courier_number = Helper::postValue('courier_number');
        $courier_link = Helper::postValue('courier_link');
        $courier_message = Helper::postValue('courier_message');

        if (!empty($id)) {

            // Comprobamos el estado de pago
            if (isset($payment_status) && !empty($payment_status)) {
                // En caso de enviar pagado sin confirmación lo cambiamos a pagado
                $payment_status = ($payment_status == 3) ? 2 : $payment_status;

                // Actualizamos el estado de pago
                $post = array(
                    'payment_status' => $payment_status
                );
                Orders::findOrFail($id)->update($post);
            }

            // Comprobamos si el estado es igual a pagado para modificar el despacho
            $order = Orders::findOrFail($id);
            if ($order) {
                if ($order->payment_status == 2) {
                    if (isset($shipping_status) && !empty($shipping_status)) {
                        // Actualizamos el estado de despacho
                        $post = array(
                            'shipping_status' => $shipping_status
                        );
                        Orders::findOrFail($id)->update($post);
                    }

                    // Comprobamos si el estado de despacho es igual a enviado para guardar las variables
                    $order = Orders::findOrFail($id);
                    if ($order) {
                        if ($order->shipping_type == 1 && $order->payment_status == 2 && $order->shipping_status == 4) {
                            if (!empty($couriers_id) && !empty($courier_number) && !empty($courier_link) && !empty($courier_message)) {
                                Capsule::select('DELETE FROM orders_couriers WHERE orders_id = :orders_id', array(':orders_id' => $id));

                                $post = array(
                                    'orders_id' => $id,
                                    'couriers_id' => $couriers_id,
                                    'number' => $courier_number,
                                    'link' => $courier_link,
                                    'message' => $courier_message
                                );
                                OrdersCouriers::create($post);
                            }
                        }

                        // Notificación de correo electronico
                        if ($order->shipping_status != 1) {

                            $switchSendEmail = false;

                            $shipping = OrdersCouriers::where(['orders_id' => $id])->first();
                            $metadata = Metadata::where(['id' => 1])->orderBy('id', 'desc')->first();
                            $configuration = Configurations::where(['id' => 1])->orderBy('id', 'desc')->first();

                            $src_map = '';
                            if (!empty($configuration->map)) {
                                preg_match('/src="([^"]+)"/', $configuration->map, $match);
                                $src_map = $match[1];
                            }

                            $WebMailVentas = $configuration->sale_email;
                            $emailFrom = '';
                            $arrayEmails = explode(',', $WebMailVentas);

                            if (is_array($arrayEmails)) {
                                foreach ($arrayEmails as $emailDestinatary) {
                                    $emailFrom = $emailDestinatary;
                                    break;
                                }
                            } else {
                                $emailFrom = $arrayEmails;
                            }

                            $URL = BASE_URL_ROOT;
                            $WebFecha = date("Y");
                            $WebTitulo = APP_COMPANY;

                            $name_ecommerce = APP_COMPANY;
                            $url_ecommerce = BASE_URL;
                            
                            $link_order = route('ver-pedido',$order->id);

                            $CSS_TABLE_MAIN = 'style="margin-left: auto; margin-right: auto; padding: 0; box-shadow: 0 0 10px rgba(0,0,0,.2); font-family: sans-serif; font-size: 14px; background: #FFF; border: 1px solid #ddd; width: 635px; color: #555555; line-height: 18px; border-spacing: 0; border-radius: 6px;"';
                            $CSS_H1_MAIN = 'style="margin: 10px 0; color: ' . MAIL_COLOR_TEXT . '; font-size: 26px;"';
                            $CSS_H1_MAIN_STRONG = 'style="color:#555"';
                            $CSS_HR = 'style="display: block; border: none; border-top: 2px solid #f2f2f2;"';
                            $CSS_TABLE_SECONDARY = 'style="width: 100%; border: 1px solid #ddd; border-bottom: 0; border-spacing: 0; font-size: 12px; line-height: 16px;"';
                            $CSS_FOOTER = 'style="display: block; padding: 10px; margin: 0; background: ' . MAIL_COLOR_TEXT . '; color: #FFF; text-align: center;font-size:12px;"';
                            $CSS_FOOTER_LINK = 'style="color: #FFF; text-decoration:none;"';
                            $CSS_TABLE_SECONDARY_BODY_TH = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color:' . MAIL_COLOR_BACKGROUND . '; text-align: left; border-right: 1px solid #ddd;"';
                            $CSS_TABLE_SECONDARY_BODY_TD = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color: #fff; text-align: left; border-right: 1px solid #ddd;"';

                            $CSS_TABLE_PRODUCTS = 'style="width: 100%; border: 1px solid #ddd; border-bottom: 0; border-spacing: 0; font-size: 12px; line-height: 16px;"';
                            $CSS_TABLE_PRODUCTS_TR = 'style="border-bottom: 1px solid #ddd; vertical-align: top;"';
                            $CSS_TABLE_PRODUCTS_THEAD_TD_LEFT = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color: #f6f6f6; border-right: 1px solid #ddd; text-align: left;"';
                            $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color: #f6f6f6; border-right: 1px solid #ddd; text-align: right;"';

                            $CSS_TABLE_PRODUCTS_TBODY_TD_LEFT = 'style="border-bottom: 1px solid #ddd; padding: 5px; border-right: 1px solid #ddd; text-align: left;"';
                            $CSS_TABLE_PRODUCTS_TBODY_TD_CENTER = 'style="border-bottom: 1px solid #ddd; padding: 5px; border-right: 1px solid #ddd; text-align: center;"';
                            $CSS_TABLE_PRODUCTS_TBODY_TD_RIGHT = 'style="border-bottom: 1px solid #ddd; padding: 5px; border-right: 1px solid #ddd; text-align: right;"';

                            $CSS_TABLE_PRODUCTS_TFOOT_TITULO = 'style="border-bottom: 1px solid #ddd; padding: 5px; border-right: 1px solid #ddd;text-align: right; font-weight: bold"';
                            $CSS_TABLE_PRODUCTS_TFOOT_DATO = 'style="border-bottom: 1px solid #ddd; padding: 5px; text-align: right; font-weight: bold"';

                            $CSS_H4 = 'style="margin: 10px 0 0; color: ' . MAIL_COLOR_TEXT . '; font-size: 16px;"';

                            switch ($order->shipping_status) {
                                case 2: // Compra Aprobada

                                    $subject_custom = 'Recepción de compra N°' . $order->id;
                                    $message_shipping = 'Su pedido se ha procesado de manera parcial con <strong>orden de compra N°' . $order->id . '</strong> por un <strong>monto de $ ' . Helper::formatDecimals($order->total, 0) . ' pesos</strong>, el cual se encuentra <strong>pagado satisfactoriamente</strong>.';

                                    $products = OrdersDetails::where(['orders_id' => $id])->get();

                                    $office_name = $order->office['title']. ' ' . $order->office['description'];

                                    $total_subtotal = $order->subtotal;
                                    $total_discount = $order->discount;
                                    $total_extra = $order->extra;
                                    $total_shipping = $order->shipping;
                                    $total_tax = $order->tax;
                                    $total_buy = $order->total;

                                    $date = Helper::dateFormatUser($order->created_at);
                                    $business_name = $order->business_name;
                                    $rut = $order->rut;
                                    $commercial_business = $order->commercial_business;
                                    $email = $order->email;
                                    $phone = $order->phone;

                                    $address = $order->address;
                                    $address_number = $order->address_number;
                                    $office_number = $order->office_number;
                                    $region_name = $order->region_name;
                                    $province_name = $order->province_name;
                                    $location_name = $order->location_name;

                                    $shipping_type = $order->shipping_type;
                                    $payment_type = $order->payment_type;

                                    $shipping_type_name = Helper::getTypeShipping($order->shipping_type);
                                    $payment_type_name = Helper::getTypePayment($order->payment_type);

                                    $order_comment = $order->order_comment;
                                    $shipping_comment = $order->shipping_comment;

                                    $shipping_comment = str_replace('class="color-danger"', '', $shipping_comment);
                                    $shipping_comment = str_replace('<hr>', '<br>', $shipping_comment);
                                    $shipping_comment = str_replace('<p>', '', $shipping_comment);
                                    $shipping_comment = str_replace('</p>', '<br>', $shipping_comment);
                                    $shipping_comment = str_replace('.<br>', '. ', $shipping_comment);

                                    $shipping_free = (strpos(strip_tags($shipping_comment), 'Costo de Despacho : GRATIS.') === false) ? false : true;

                                    $type_document = $order->type_document;
                                    $type_document_name = Helper::getTypeDocumentSII($order->type_document);

                                    $document_business_name = $order->document_business_name;
                                    $document_rut = $order->document_rut;
                                    $document_commercial_business = $order->document_commercial_business;
                                    $document_phone = $order->document_phone;
                                    $document_address = $order->document_address;
                                    $document_address_number = $order->document_address_number;
                                    $document_office_number = $order->document_office_number;
                                    $document_region_name = $order->document_region_name;
                                    $document_province_name = $order->document_province_name;
                                    $document_location_name = $order->document_location_name;

                                    $discount_code = $order->discount_code;

                                    //----------------

                                    $content_products_head = '';

                                    $content_products_head .= '<tr ' . $CSS_TABLE_PRODUCTS_TR . '>';
                                    $content_products_head .= '<th ' . $CSS_TABLE_PRODUCTS_THEAD_TD_LEFT . '>Código</th>';
                                    $content_products_head .= '<th ' . $CSS_TABLE_PRODUCTS_THEAD_TD_LEFT . '>Producto</th>';
                                    $content_products_head .= '<th ' . $CSS_TABLE_PRODUCTS_THEAD_TD_LEFT . '>Cantidad</th>';
                                    $content_products_head .= '<th ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '>P. Unitario</th>';
                                    $content_products_head .= '<th ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '>P. Total</th>';
                                    $content_products_head .= '</tr>';


                                    $content_products = '';

                                    if ($products->count() > 0) {
                                        foreach ($products as $product) {
                                            $code = $product->code;
                                            $description = $product->description;
                                            $quantity = $product->quantity;
                                            $unit_price = Helper::formatDecimals($product->unit_price, 0);
                                            $total_price = Helper::formatDecimals($product->total_price, 0);

                                            $content_products .= '<tr ' . $CSS_TABLE_PRODUCTS_TR . '>';
                                            $content_products .= '<td ' . $CSS_TABLE_PRODUCTS_TBODY_TD_LEFT . '>' . $code . '</td>';
                                            $content_products .= '<td ' . $CSS_TABLE_PRODUCTS_TBODY_TD_LEFT . '>' . $description . '</td>';
                                            $content_products .= '<td ' . $CSS_TABLE_PRODUCTS_TBODY_TD_CENTER . '>' . $quantity . '</td>';
                                            $content_products .= '<td ' . $CSS_TABLE_PRODUCTS_TBODY_TD_RIGHT . '>$ ' . $unit_price . '</td>';
                                            $content_products .= '<td ' . $CSS_TABLE_PRODUCTS_TBODY_TD_RIGHT . '>$ ' . $total_price . '</td>';
                                            $content_products .= '</tr>';
                                        }
                                    }

                                    $content_products_footer = '';

                                    $content_products_footer .= '<tr>';
                                    $content_products_footer .= '<td colspan="4" ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>Subtotal</strong></td>';
                                    $content_products_footer .= '<td ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>$ ' . Helper::formatDecimals($total_subtotal, 0) . '</strong></td>';
                                    $content_products_footer .= '</tr>';

                                    if ($total_discount > 0) {
                                        $content_products_footer .= '
                                        <tr>
                                        <td colspan="4" ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>Descuento</strong></td>
                                        <td ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>$ ' . Helper::formatDecimals($total_discount, 0) . '</strong></td>
                                        </tr>
                                        ';
                                    }

                                    if ($configuration->active_tax == 1) {
                                        $content_products_footer .= '
                                        <tr>
                                        <td colspan="4" ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>IVA</strong></td>
                                        <td ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>$ ' . Helper::formatDecimals($total_tax, 0) . '</strong></td>
                                        </tr>
                                        ';
                                    }

                                    if ($shipping_type == 2 || ($shipping_free == true || $total_shipping > 0)) {
                                        $content_products_footer .= '
                                        <tr>
                                        <td colspan="4" ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>Despacho</strong></td>
                                        <td ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>$ ' . Helper::formatDecimals($total_shipping, 0) . '</strong></td>
                                        </tr>
                                        ';
                                    }

                                    $content_products_footer .= '
                                    <tr>
                                    <td colspan="4" ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>Recargo</strong></td>
                                    <td ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>$ ' . Helper::formatDecimals($total_extra, 0) . '</strong></td>
                                    </tr>
                                    ';

                                    $content_products_footer .= '
                                    <tr>
                                    <td colspan="4" ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>Total</strong></td>
                                    <td ' . $CSS_TABLE_PRODUCTS_THEAD_TD_RIGHT . '><strong>$ ' . Helper::formatDecimals($total_buy, 0) . '</strong></td>
                                    </tr>
                                    ';

                                    $message_custom = '
                                    <tr><!-- CONTENIDO TITULAR -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <h4 ' . $CSS_H4 . '>Detalle General</h4>
                                       </td>
                                    </tr>
                        
                                    <tr><!-- CONTENIDO -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <table width="100%" border="0" ' . $CSS_TABLE_SECONDARY . '>
                                            <tbody>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th width="30%" ' . $CSS_TABLE_SECONDARY_BODY_TH . '>N° Solicitud</th>
                                                   <td width="70%" ' . $CSS_TABLE_SECONDARY_BODY_TD . '>#' . $id . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Fecha</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $date . '</td>
                                                </tr>';

                                    if (!empty($office_name)) {

                                        $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                  <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Sucursal</th>
                                                  <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $office_name . '</td>
                                                </tr>';
                                    }

                                    $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                  <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Tipo Facturación</th>
                                                  <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $type_document_name . '</td>
                                                </tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                <tr>
                                                  <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Método de Despacho</th>
                                                  <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $shipping_type_name . '</td>
                                                </tr>';

                                    if (!empty($order_comment)) {

                                        $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                  <th ' . $CSS_TABLE_SECONDARY_BODY_TH . ' valign="top">Observaciones de Despacho</th>
                                                  <td ' . $CSS_TABLE_SECONDARY_BODY_TD . ' valign="top">' . $shipping_comment . '</td>
                                                </tr>';
                                    }

                                    $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                  <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Método de Pago</th>
                                                  <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $payment_type_name . '</td>
                                                </tr>';

                                    if (!empty($order_comment)) {

                                        $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                  <th ' . $CSS_TABLE_SECONDARY_BODY_TH . ' valign="top">Observaciones de Pedido</th>
                                                  <td ' . $CSS_TABLE_SECONDARY_BODY_TD . ' valign="top">' . $order_comment . '</td>
                                                </tr>';
                                    }

                                    if (!empty($discount_code)) {

                                        $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                  <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Cupón Utilizado</th>
                                                  <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $discount_code . '</td>
                                                </tr>';
                                    }

                                    $message_custom .= '                                     
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    
                                    <tr><!-- SEPARADOR -->
                                       <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO TITULAR -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <h4 ' . $CSS_H4 . '>Detalle de Cliente</h4>
                                       </td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <table width="100%" border="0" ' . $CSS_TABLE_SECONDARY . '>
                                            <tbody>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th width="30%" ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Nombre o Razón Social</th>
                                                   <td width="70%" ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $business_name . '</td>
                                                </tr> 
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Rut</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $rut . '</td>
                                                </tr>';

                                    if (!empty($commercial_business)) {

                                        $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Giro</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $commercial_business . '</td>
                                                </tr>';
                                    }

                                    $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Email</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $email . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Teléfono</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $phone . '</td>
                                                </tr>                                        
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    ';

                                    if ($shipping_type == 1) {

                                        $message_custom .= '   
                                    <tr><!-- SEPARADOR -->
                                       <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO TITULAR -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <h4 ' . $CSS_H4 . '>Detalle de Despacho</h4>
                                       </td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <table width="100%" border="0" ' . $CSS_TABLE_SECONDARY . '>
                                            <tbody>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th width="30%" ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Dirección</th>
                                                   <td width="70%" ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $address . '</td>
                                                </tr> 
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Numeración de Calle</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $address_number . '</td>
                                                </tr>';

                                        if (!empty($office_number)) {

                                            $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Departamento / Oficina</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $office_number . '</td>
                                                </tr>';
                                        }

                                        $message_custom .= '
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Región</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $region_name . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Provincia</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $province_name . '</td>
                                                </tr> 
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Comuna / Localidad</th>
                                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $location_name . '</td>
                                                </tr>                                        
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    ';
                                    }

                                    if ($type_document == 2) {

                                        $message_custom .= '   
                                    <tr><!-- SEPARADOR -->
                                       <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO TITULAR -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <h4 ' . $CSS_H4 . '>Detalle de Facturación</h4>
                                       </td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <table width="100%" border="0" ' . $CSS_TABLE_SECONDARY . '>
                                            <tbody>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                    <th width="30%" ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Razón Social</th>
                                                    <td width="70%" ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_business_name . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Rut</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_rut . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Giro</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_commercial_business . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Teléfono</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_phone . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Dirección</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_address . '</td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Numeración de Calle</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_address_number . '</td>
                                                </tr>
                                                ';

                                        if (!empty($document_office_number)) {

                                            $message_custom .= '
                                                <tr>
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Departamento / Oficina</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_office_number . '</td>
                                                </tr>';
                                        }

                                        $message_custom .= '
                                                <tr>
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Región</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_region_name . '</td>
                                                </tr>
                                                <tr>
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Provincia</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_province_name . '</td>
                                                </tr>
                                                <tr>
                                                    <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Comuna / Localidad</th>
                                                    <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $document_location_name . '</td>
                                                </tr>                                        
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    ';
                                    }

                                    $message_custom .= '      
                                    <tr><!-- SEPARADOR -->
                                       <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO TITULAR -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <h4 ' . $CSS_H4 . '>Detalle de Productos</h4>
                                       </td>
                                    </tr>
                                    
                                    <tr><!-- CONTENIDO -->
                                       <td style="display: block; padding: 5px 15px;">
                                          <table width="100%" border="0" ' .  $CSS_TABLE_PRODUCTS . '>
                                            <thead>
                                                ' . $content_products_head . '    
                                            </thead>
                                            <tbody>
                                                ' . $content_products . '     
                                                ' . $content_products_footer . '                                
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    ';

                                    $switchSendEmail = true;

                                    break;
                                case 3: // En Proceso

                                    if ($configuration->shipping_type == 'SHIPIT' && empty($order->shipit_id)) {
                                        $shipit = new ShipitCustom($configuration->shipit_email, $configuration->shipit_token, $configuration->shipit_environment);
                                        $size = $shipit->getSizePacking($order);
                                        $response = $shipit->generateOT($order, $size);

                                        if (isset($response->id) && !empty($response->id)) {
                                            Orders::findOrFail($order->id)->update(['shipit_id' => $response->id, 'shipping_status' => 3]);

                                            $subject_custom = 'Notificación de despacho en proceso';
                                            $message_shipping = 'Su pedido con Orden de Compra N°' . $order->id . ' se encuentra en proceso de ser despachado.';

                                            $message_custom = '
                                            <tr><!-- SEPARADOR -->
                                               <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                            </tr>
                                            <tr><!-- SEPARADOR -->
                                                <td style="display: block; padding: 15px; text-align: center; font-size:12px; ">
                                                  Recuerda que puedes revisar el estado de tu despacho en todo momento haciendo click en el siguiente botón.<br>
                                                  recuerda tener tu sesión abierta para ser redirigido al detalle de tu compra".<br>
                                                  <a href="' . $link_order . '" target="_blank">Ver Pedido Detallado Aquí</a>
                                               </td>
                                            </tr>
                                            ';

                                            $switchSendEmail = true;
                                        }
                                    } else {
                                        $subject_custom = 'Notificación de despacho en proceso';
                                        $message_shipping = 'Su pedido con Orden de Compra N°' . $order->id . ' se encuentra en proceso de ser despachado.';

                                        $message_custom = '
                                        <tr><!-- SEPARADOR -->
                                           <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                        </tr>
                                        <tr><!-- SEPARADOR -->
                                            <td style="display: block; padding: 15px; text-align: center; font-size:12px; ">
                                              Recuerda que puedes revisar el estado de tu despacho en todo momento haciendo click en el siguiente botón.<br>
                                              recuerda tener tu sesión abierta para ser redirigido al detalle de tu compra".<br>
                                              <a href="' . $link_order . '" target="_blank">Ver Pedido Detallado Aquí</a>
                                           </td>
                                        </tr>
                                        ';

                                        $switchSendEmail = true;
                                    }

                                    break;
                                case 4: // Enviado - Listo para retiro

                                    if ($configuration->shipping_type == 'PROPIO') {
                                        $subject_custom = ($order->shipping_type == 2) ? 'Notificación de despacho listo para retiro' : 'Notificación de despacho en traslado';
                                        $message_shipping = ($order->shipping_type == 2) ? 'Su pedido con Orden de Compra N°' . $order->id . ', está listo para ser retirado en nuestra tienda, a continuación el detalle de los productos asociados.' : 'Su pedido con Orden de Compra N°' . $order->id . ', ya se encuentra en traslado hacia su destino.<br>Su número de seguimiento es: ' . $shipping->number . '. Puedes revisar adicionalmente su seguimiento en el sitio web de ' . $shipping->courier->title . ' accediento <a href="' . $shipping->link . '" target="_blank">aquí</a><br><br>Observación: ' . $shipping->message . '';

                                        $message_custom = '
                                        <tr><!-- SEPARADOR -->
                                           <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                        </tr>
                                        <tr><!-- SEPARADOR -->
                                            <td style="display: block; padding: 15px; text-align: center; font-size:12px; ">
                                              Recuerda que puedes revisar el estado de tu despacho en todo momento haciendo click en el siguiente botón.<br>
                                              "recuerda tener tu sesión abierta para ser redirigido al detalle de tu compra".<br>
                                              <a href="' . $link_order . '" target="_blank">Ver Pedido Detallado Aquí</a>
                                           </td>
                                        </tr>
                                        ';

                                        if ($order->shipping_type == 2) {
                                            $message_custom .= '
                                            <tr><!-- SEPARADOR -->
                                            <td style="display: block; padding: 15px; text-align: center; font-size:12px; ">
                                                  <h3 style="margin: 10px 0 10px; font-size: 18px;">Ubicación para Retiro</h3>
                                                      <iframe style="display: block; width:100%; height: 200px;" src="' . $src_map . '" width="1200" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
                                               </td>
                                            </tr>
                                            ';
                                        }

                                        $switchSendEmail = true;
                                    }

                                    break;
                                case 5: // Entregado

                                    if ($configuration->shipping_type == 'PROPIO') {
                                        $subject_custom = ($order->shipping_type == 2) ? 'Notificación de pedido retirado' : 'Notificación de pedido entregado';
                                        $message_shipping = ($order->shipping_type == 2) ? 'El proceso de entrega su pedido con Orden de Compra N°' . $order->id . ' ha sido completado.' : 'El proceso de entrega su pedido con Compra N°' . $order->id . ' ha sido completado.';

                                        $message_custom = '
                                        <tr><!-- SEPARADOR -->
                                           <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                        </tr>
                                        <tr><!-- SEPARADOR -->
                                            <td style="display: block; padding: 15px; text-align: center; font-size:12px; ">
                                              Recuerda que puedes revisar el estado de tu despacho en todo momento haciendo click en el siguiente botón.<br>
                                              "recuerda tener tu sesión abierta para ser redirigido al detalle de tu compra".<br>
                                              <a href="' . $link_order . '" target="_blank">Ver Pedido Detallado Aquí</a>
                                           </td>
                                        </tr>
                                        ';

                                        $switchSendEmail = true;
                                    }

                                    break;
                            }

                            if ($switchSendEmail == true) {
                                $mail = new PHPMailer();
                                $mail->CharSet = 'UTF-8';
                                $mail->Encoding = 'base64';

                               $mail->AddEmbeddedImage(ROOT . 'public' . DS . 'themes' . DS . DEFAULT_LAYOUT . DS . 'images' . DS . 'header.jpg', 'imgHeader', 'attachment', 'base64', 'image/jpeg');

                                $mail->From = $emailFrom;
                                $mail->FromName = utf8_encode("=?UTF-8?B?" . base64_encode(APP_NAME) . "?=");
                                $asunto = $subject_custom . ' - ' . APP_NAME;
                                $mail->Subject = utf8_encode('=?UTF-8?B?' . base64_encode($asunto) . '?=');
                                $mail->AddAddress($order->email, $order->business_name);

                                $body = '
                                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http: //www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                                <html xmlns="http: //www.w3.org/1999/xhtml">
                                
                                   <head>
                                      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                                      <title>Documento sin título</title>
                                   </head>
                                
                                   <body style="background: #f4f4f4; padding: 20px;">
                                      <table width="635" border="0" valign="top" ' . $CSS_TABLE_MAIN . '>
                                         <tbody>
                                            <tr>
                                               <td style="padding: 0; margin: 0; border: 0; width: 635px;">
                                                  <img src="cid:imgHeader" style="border-radius: 6px 6px 0 0; border-bottom:1px solid #f2f2f2">
                                               </td>
                                            </tr>
                                            <tr><!-- TITULAR -->
                                               <td style="display: block; padding: 5px 15px; text-align: center;">
                                                  <h1 ' . $CSS_H1_MAIN . '>' . $subject_custom . '</h1>
                                               </td>
                                            </tr>
                                
                                            <tr><!-- SEPARADOR -->
                                               <td style="display: block; padding: 0 15px;"><hr ' . $CSS_HR . '></td>
                                            </tr>
                                
                                            <tr><!-- CONTENIDO TITULAR -->
                                               <td style="display: block; padding: 5px 15px;">
                                                  <p style="font-size:12px;">' . $message_shipping . '</p>
                                               </td>
                                            </tr>
                                            
                                            ' . $message_custom . '
                                            
                                            <tr><!-- SEPARADOR -->
                                               <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                            </tr>
                                            
                                            <tr><!-- AVISO RESPUESTA AUTOMATICA -->
                                               <td style="display: block; padding: 10px; background: #f6f6f6; color:#999; margin-top: 20px; text-align: center; font-size:11px;">
                                                  Este correo se ha generado de forma automatica, favor no responder.
                                               </td>
                                            </tr>
                                
                                            <tr ><!-- FOOTER -->
                                               <td ' . $CSS_FOOTER . '>
                                                  <a href="' . $URL . '" target="_self" ' . $CSS_FOOTER_LINK . '>' . $WebFecha . ' - ' . $WebTitulo . '</a>
                                               </td>
                                            </tr>
                                         </tbody>
                                      </table>
                                
                                   </body>
                                </html>
                                ';

                                $mail->Body = utf8_decode($body);
                                $mail->IsHTML(true);

                                if ($mail->Send()) {
                                    $mail->ClearAddresses();
                                    $mail->ClearAllRecipients();
                                }
                            }
                        }
                    }
                }
            }
            session()->flash('error', 'success');
            return redirect()->route('orders.view',$id);
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('orders');
        }
    }
}

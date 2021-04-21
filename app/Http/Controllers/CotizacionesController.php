<?php

namespace App\Http\Controllers;

use App\Models\Cotizaciones;
use App\Models\CotizacionesDetalle;
use Application\Helper;
use Application\HelperExcel;
use Illuminate\Http\Request;
use PHPExcel;

class CotizacionesController extends Controller
{
    private $title = 'Cotizaciones';
    private $module = 'cotizaciones';

    public function index(){

        // --------------------- FORM POST

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document']))? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document']))? Helper::postValue('date_end_document') : date('d-m-Y');

        //--------------------------------------------------------------------------------------------------------------------------------------


        return view('admvisch.cotizaciones.index')->with(['title'=>$this->title,'module'=>$this->module, '_date_start_document'=>$_date_start_document, '_date_end_document'=>$_date_end_document ]);
    }

    public function documents(){

        $table_documents_body = array();
        $where = "";

        // --------------------- FORM POST

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document']))? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document']))? Helper::postValue('date_end_document') : date('d-m-Y');

        //--------------------------------------------------------------------------------------------------------------------------------------

        $documents = Cotizaciones::whereNotNull('cotizaciones.id');

        if(!empty($_date_start_document)){
            $documents = $documents->whereRaw("DATE(cotizaciones.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if(!empty($_date_end_document)){
            $documents = $documents->whereRaw("DATE(cotizaciones.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        $documents = $documents->orderBy('cotizaciones.id', 'DESC')->get();

        if($documents->count() > 0) {
            foreach ($documents AS $document) {

                $table_documents_body[] = array(
                    '<input type="checkbox" name="documents[]" value="' . $document->id . '">',
                    '<a style="cursor:pointer" class="show-compact-view" id="' . $document->id . '">Detalle Compacto</a>',
                    '<a href="'. route('cotizaciones.view', $document->id) .'" target="_blank"><i class="fa fa-list-ol" aria-hidden="true"></i> Ver Detalle</a>',
                    $document->id,
                    Helper::dateFormatUser($document->created_at),
                    $document->rut,
                    $document->business_name,
                    '$ ' . Helper::formatDecimals($document->total, 0)
                );

            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        echo json_encode(array(
            'data' => $table_documents_body,
        ));
    }

    public function view($id){

        $order = Cotizaciones::findOrFail($id);
        $products = CotizacionesDetalle::where(['cotizaciones_id' => $id])->get();

        $address_name  = $order->address;
        $address_name .= " N° " . $order->address_number;
        $address_name .= (!empty($order->office_number))? ", Depto / Oficina " . $order->office_number : '';
        $address_name .= ", " . $order->region_name;
        $address_name .= ", " . $order->province_name;
        $address_name .= ", " . $order->location_name;

        $order->address_full = $address_name;


        return view('admvisch.cotizaciones.view')->with(['title'=>$this->title, 'module'=>$this->module, 'order'=>$order, 'products'=>$products]);
    }

    public function viewCompact(){

        $id = Helper::postValue('id');

        $order = Cotizaciones::findOrFail($id);
        $products = CotizacionesDetalle::where(['cotizaciones_id' => $id])->get();

        $address_name = '';

        $address_name  = $order->address;
        $address_name .= " N° " . $order->address_number;
        $address_name .= (!empty($order->office_number))? ", Depto / Oficina " . $order->office_number : '';
        $address_name .= ", " . $order->region_name;
        $address_name .= ", " . $order->province_name;
        $address_name .= ", " . $order->location_name;

        $content_product = '';
        if(count($products) > 0) {
            foreach ($products AS $product) {
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
            <td colspan="4" align="right"><strong>Total</strong></td>
            <td align="right"><strong>$ ' . Helper::formatDecimals($order->total, 0) . '</strong></td>
        </tr>
        ';

        $data = '
        <div class="row">
            <div class="col-sm-12 invoice-left">
                <h3>COTIZACIÓN NO. #' . $order->id . '</h3>
                <span class="badge badge-success">' . Helper::formatDateToCompleteDateUser($order->created_at, true) . '</span>
            </div>
        </div>

        <hr class="margin" />

        <div class="row">

            <div class="col-sm-6">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Datos de Cotizante</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="30%" class="col-gris">Sucursal:</td>
                        <td width="70%">' . $order->office->title . ' ' . $order->office->description . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Razón Social:</td>
                        <td>' . $order->business_name . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Rut:</td>
                        <td>' . $order->rut . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Email:</td>
                        <td>' . $order->email . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Teléfono:</td>
                        <td>' . $order->phone . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Dirección:</td>
                        <td>' . $order->address . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Dirección:</td>
                        <td>' . $address_name . '</td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="margin"></div>

        <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle de Productos</h4>
        <table class="table table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th width="40%">Producto</th>
                <th width="10%">Código</th>
                <th width="10%">Cantidad</th>
                <th width="15%">Precio Unit.</th>
                <th width="15%">Precio Total</th>
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

        $where = "";

        $header = array();

        $header[] = "N°";
        $header[] = "Fecha";
        $header[] = "Sucursal";
        $header[] = "Nombre / Razón Social";
        $header[] = "Rut";
        $header[] = "Email";
        $header[] = "Teléfono";
        $header[] = "Dirección";
        $header[] = "Numeración Calle";
        $header[] = "Número Departamento / Oficina";
        $header[] = "Región";
        $header[] = "Provincia";
        $header[] = "Comuna";
        $header[] = "Total";
        $header[] = "Código";
        $header[] = "Producto";
        $header[] = "Cantidad";
        $header[] = "Precio Unitario";
        $header[] = "Precio Total";

        $objPHPExcel = new PHPExcel();
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

        //-----------------

        $documents = CotizacionesDetalle::join('cotizaciones', 'cotizaciones_detalle.cotizaciones_id', '=', 'cotizaciones.id')
            ->join('offices', 'cotizaciones.offices_id', '=', 'offices.id', 'LEFT OUTER')
            ->select([
                'cotizaciones.id',
                'cotizaciones.created_at',
                'cotizaciones.business_name',
                'cotizaciones.rut',
                'cotizaciones.email',
                'cotizaciones.phone',
                'cotizaciones.address',
                'cotizaciones.address_number',
                'cotizaciones.office_number',
                'cotizaciones.region_name',
                'cotizaciones.province_name',
                'cotizaciones.location_name',
                'cotizaciones.total',
                'cotizaciones_detalle.code',
                'cotizaciones_detalle.description',
                'cotizaciones_detalle.combination',
                'cotizaciones_detalle.quantity',
                'cotizaciones_detalle.unit_price',
                'cotizaciones_detalle.total_price',
                'offices.title AS office_title',
                'offices.description AS office_description'
            ])
            ->whereNotNull('cotizaciones.id');

        if(!empty($_date_start_document)){
            $documents = $documents->whereRaw("DATE(cotizaciones.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if(!empty($_date_end_document)){
            $documents = $documents->whereRaw("DATE(cotizaciones.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'");
        }

        if($selected == 1){

            $_documents = '';

            if(isset($_POST['documents']) && !empty($_POST['documents'])){
                if(count($_POST['documents']) > 0){
                    $_documents = implode(',', $_POST['documents']);
                }
            }

            if(!empty($_documents)){
                $documents = $documents->whereIn("cotizaciones.id", $_documents);
            }
        }

        $documents = $documents->orderBy('cotizaciones.id', 'DESC')->get();

        //-----------

        if ($documents->count() > 0) {
            foreach ($documents AS $document) {
                $data = array();

                $data[] = $document->id;
                $data[] = $document->created_at;
                $data[] = $document->office_title . ' ' . $document->office_description;
                $data[] = $document->business_name;
                $data[] = $document->rut;
                $data[] = $document->email;
                $data[] = $document->phone;
                $data[] = $document->address;
                $data[] = $document->address_number;
                $data[] = $document->office_number;
                $data[] = $document->region_name;
                $data[] = $document->province_name;
                $data[] = $document->location_name;
                $data[] = '$ ' . Helper::formatDecimals($document->total, 0);
                $data[] = $document->code;
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

        HelperExcel::applyExcelOutput($objPHPExcel, "cotizaciones");
    }
}

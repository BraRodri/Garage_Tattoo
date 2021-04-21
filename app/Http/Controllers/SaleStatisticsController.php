<?php

namespace App\Http\Controllers;

use Application\Helper;

use Illuminate\Http\Request;
use App\Models\Orders;
use HighChart\HighchartsPHP\Highchart;
use HighChart\HighchartsPHP\HighchartJsExpr;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

class SaleStatisticsController extends Controller
{
    private $title = 'Estadísticas de Ordenes de Compras';
    private $module = 'saleStatistics';

    public function index(){


        $error ="";
        // --------------------- FORM POST

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document']))? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document']))? Helper::postValue('date_end_document') : date('d-m-Y');

        if($_date_start_document > $_date_end_document){
            $_date_start_document = $_date_end_document;
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $data_categories1 = $data_values1 = array();

        $documents = DB::select("
        SELECT
        LPAD(MONTH(created_at), 2, 0) AS MES_PERIODO,
        YEAR(created_at) AS ANNIO_PERIODO,
        COUNT(*) AS NUMERO_VENTAS_MENSUALES
        FROM
        orders
        WHERE
        payment_status IN(1,2,3)
        AND DATE(created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'
        AND DATE(created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'
        GROUP BY
        MONTH(created_at),
        YEAR(created_at)
        ORDER BY
        YEAR(created_at) ASC,
        LPAD(MONTH(created_at), 2, 0) ASC
        ");


        if(count($documents) > 0){
            foreach($documents AS $document){
                $data_categories1[] = ucfirst(Helper::monthToMonthName($document->MES_PERIODO)) . ' ' . $document->ANNIO_PERIODO;
                $data_values1[] = $document->NUMERO_VENTAS_MENSUALES;
            }
        }

       $languageExport = loadLanguage();

        $chart = new  Highchart();

        $chart->lang->loading = $languageExport["loading"];
        $chart->lang->exportButtonTitle = $languageExport["exportButtonTitle"];
        $chart->lang->printButtonTitle = $languageExport["printButtonTitle"];
        $chart->lang->rangeSelectorFrom = $languageExport["rangeSelectorFrom"];
        $chart->lang->rangeSelectorTo = $languageExport["rangeSelectorTo"];
        $chart->lang->rangeSelectorZoom = $languageExport["rangeSelectorZoom"];
        $chart->lang->downloadPNG = $languageExport["downloadPNG"];
        $chart->lang->downloadJPEG = $languageExport["downloadJPEG"];
        $chart->lang->downloadPDF = $languageExport["downloadPDF"];
        $chart->lang->downloadSVG = $languageExport["downloadSVG"];
        $chart->lang->printChart = $languageExport["printChart"];
        $chart->lang->thousandsSep = $languageExport["thousandsSep"];
        $chart->lang->decimalPoint = $languageExport["decimalPoint"];

        $chart->includeExtraScripts(array('exporting'));

        $chart->chart->renderTo = "container1";
        $chart->chart->type = "column";
        $chart->title->text = "Ordenes de Compras Mensuales";
        $chart->subtitle->text = str_replace('-', '/', $_date_start_document) . " al " . str_replace('-', '/', $_date_end_document);

        $chart->exporting->buttons->contextButton->x = -15;

        $chart->xAxis->categories = $data_categories1;

        $chart->yAxis->min = 0;
        $chart->yAxis->title->text = "Número de Ordenes de Compras";
        $chart->legend->layout = "vertical";
        $chart->legend->backgroundColor = "#FFFFFF";
        $chart->legend->align = "left";
        $chart->legend->verticalAlign = "top";
        $chart->legend->x = 100;
        $chart->legend->y = 70;
        $chart->legend->floating = 1;
        $chart->legend->shadow = 1;
        $chart->legend->enabled = false;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '' + this.x +': '+ this.y +' ventas';}");

        $chart->plotOptions->column->pointPadding = 0.2;
        $chart->plotOptions->column->borderWidth = 0;

        $chart->plotOptions->column->dataLabels->style = array('textShadow' => 0);
        $chart->plotOptions->column->shadow = false;
        $chart->plotOptions->column->dataLabels->enabled = 1;
        $chart->plotOptions->column->dataLabels->shadow = false;
        $chart->plotOptions->column->dataLabels->formatter = new HighchartJsExpr("function() {
        return '' + Highcharts.numberFormat(this.point.y,0,',','.');
        }");

        $chart->series[] = array(
            'name' => "Ordenes de Compras",
            'data' => $data_values1,
            'color' => Helper::getColorRandom()
        );

        $chart->credits->enabled = false;

        //---------------------------------

        $data_categories2 = $data_values2 = array();
        $datosPedidosDiarias = array();

        $date = explode('-', $_date_end_document);
        $mes = $date[1];
        $annio = $date[2];
        $dia = cal_days_in_month(CAL_GREGORIAN, $mes, $annio);

        for($i=1;$i<=$dia;$i++) {
            if($i < 10){ $i = '0' . $i; }
            $datosPedidosDiarias[($i)] = 0;
        }

        $documents = DB::select("
        SELECT
        LPAD(DAY(created_at), 2, 0) AS DIA_PERIODO,
        LPAD(MONTH(created_at), 2, 0) AS MES_PERIODO,
        YEAR(created_at) AS ANNIO_PERIODO,
        COUNT(*) AS NUMERO_VENTAS_DIARIAS
        FROM
        orders
        WHERE
        shipping_status IN(1, 2,3)
        AND MONTH(created_at) = '" . $mes . "'
        AND YEAR(created_at) = '". $annio . "'
        GROUP BY
        DAY(created_at),
        MONTH(created_at),
        YEAR(created_at)
        ORDER BY
        YEAR(created_at) ASC,
        LPAD(MONTH(created_at), 2, 0) ASC,
        DAY(created_at) ASC
        ");
        if(count($documents) > 0){
            foreach($documents AS $document){
                $datosPedidosDiarias[$document->DIA_PERIODO] = $document->NUMERO_VENTAS_DIARIAS;
            }
        }

        $datosPedidosDiariasJquery = array();

        foreach($datosPedidosDiarias AS $indice => $valor){
            $data_categories2[] = $indice;
            $data_values2[] = $valor;
        }

        $chart2 = new Highchart();

        $chart2->lang->loading = $languageExport["loading"];
        $chart2->lang->exportButtonTitle = $languageExport["exportButtonTitle"];
        $chart2->lang->printButtonTitle = $languageExport["printButtonTitle"];
        $chart2->lang->rangeSelectorFrom = $languageExport["rangeSelectorFrom"];
        $chart2->lang->rangeSelectorTo = $languageExport["rangeSelectorTo"];
        $chart2->lang->rangeSelectorZoom = $languageExport["rangeSelectorZoom"];
        $chart2->lang->downloadPNG = $languageExport["downloadPNG"];
        $chart2->lang->downloadJPEG = $languageExport["downloadJPEG"];
        $chart2->lang->downloadPDF = $languageExport["downloadPDF"];
        $chart2->lang->downloadSVG = $languageExport["downloadSVG"];
        $chart2->lang->printChart = $languageExport["printChart"];
        $chart2->lang->thousandsSep = $languageExport["thousandsSep"];
        $chart2->lang->decimalPoint = $languageExport["decimalPoint"];

        $chart2->includeExtraScripts(array('exporting'));

        $chart2->chart->renderTo = "container2";
        $chart2->chart->type = "column";
        $chart2->title->text = "Ordenes de Compras Diarias";
        $chart2->subtitle->text = "01/" . $mes . "/" . $annio . " al " . $dia . "/" . $mes . "/" . $annio;

        $chart2->exporting->buttons->contextButton->x = -15;

        $chart2->xAxis->categories = $data_categories2;

        $chart2->yAxis->min = 0;
        $chart2->yAxis->title->text = "Número de Ordenes de Compras";
        $chart2->legend->layout = "vertical";
        $chart2->legend->backgroundColor = "#FFFFFF";
        $chart2->legend->align = "left";
        $chart2->legend->verticalAlign = "top";
        $chart2->legend->x = 100;
        $chart2->legend->y = 70;
        $chart2->legend->floating = 1;
        $chart2->legend->shadow = 1;
        $chart2->legend->enabled = false;

        $chart2->tooltip->formatter = new HighchartJsExpr("function() {
        return '' + this.x +': '+ this.y +' ventas';}");

        $chart2->plotOptions->column->pointPadding = 0.2;
        $chart2->plotOptions->column->borderWidth = 0;

        $chart2->plotOptions->column->dataLabels->style = array('textShadow' => 0);
        $chart2->plotOptions->column->shadow = false;
        $chart2->plotOptions->column->dataLabels->enabled = 1;
        $chart2->plotOptions->column->dataLabels->shadow = false;
        $chart2->plotOptions->column->dataLabels->formatter = new HighchartJsExpr("function() {
        return '' + Highcharts.numberFormat(this.point.y,0,',','.');
        }");

        $chart2->series[] = array(
            'name' => "Ordenes de Compras",
            'data' => $data_values2,
            'color' => Helper::getColorRandom()
        );

        $chart2->credits->enabled = false;

        //--------------------------

        $data_categories3 = $data_values3 = array();

        $documents = DB::select("
        SELECT
        COUNT(*) AS NUMERO_PRODUCTO,
        orders_details.`code`,
        orders_details.description,
        orders_details.combination
        FROM
        orders_details
        INNER JOIN orders ON orders_details.orders_id = orders.id
        WHERE
        orders.payment_status IN(1, 2,3)
        AND DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'
        AND DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'
        GROUP BY
        orders_details.`code`
        ORDER BY
        NUMERO_PRODUCTO DESC
        LIMIT 15
        ");
        if(count($documents) > 0) {
            foreach ($documents AS $document) {
                $data_categories3[$document->code] = array(
                    'code' => $document->code,
                    'description' => $document->description,
                    'total' => $document->NUMERO_PRODUCTO
                );
            }
        }

        foreach($data_categories3 AS $indice => $valor){
            $data_values3[] = array($indice . ' (' . $valor['description'] . ')', $valor['total']);
        }

        $chart3 = new Highchart();

        $chart3->chart->renderTo = "container3";
        $chart3->chart->plotBackgroundColor = null;
        $chart3->chart->plotBorderWidth = null;
        $chart3->chart->plotShadow = false;
        $chart3->title->text = "Productos más vendido desde " . str_replace('-', '/', $_date_start_document) . " al " . str_replace('-', '/', $_date_end_document);

        $chart3->tooltip->formatter = new HighchartJsExpr(
            "function() {
        return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,0,',','.') +' %';}");

        $chart3->plotOptions->pie->allowPointSelect = 1;
        $chart3->plotOptions->pie->cursor = "pointer";
        $chart3->plotOptions->pie->dataLabels->enabled = 1;
        $chart3->plotOptions->pie->dataLabels->color = "#000000";
        $chart3->plotOptions->pie->dataLabels->connectorColor = "#000000";

        $chart3->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
            "function() {
        return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,0,',','.') +' %'; }");

        $chart3->series[] = array(
            'type' => "pie",
            'name' => "Productos",
            'data' => $data_values3
        );

        $chart3->credits->enabled = false;

        //--------------------------

        $data_categories4 = $data_values4 = array();

        $documents = DB::select("
        SELECT
        COUNT(*) AS NUMERO_PRODUCTO,
        orders_details.`code`,
        orders_details.description,
        orders_details.combination
        FROM
        orders_details
        INNER JOIN orders ON orders_details.orders_id = orders.id
        WHERE
        orders.payment_status IN(1, 2,3)
        AND DATE(orders.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'
        AND DATE(orders.created_at) <= '" . Helper::dateFormatSystem($_date_end_document) . "'
        GROUP BY
        orders_details.`code`
        ORDER BY
        NUMERO_PRODUCTO ASC
        LIMIT 15
        ");
        if(count($documents) > 0) {
            foreach ($documents AS $document) {
                $data_categories4[$document->code] = array(
                    'code' => $document->code,
                    'description' => $document->description,
                    'total' => $document->NUMERO_PRODUCTO
                );
            }
        }

        foreach($data_categories4 AS $indice => $valor){
            $data_values4[] = array($indice . ' (' . $valor['description'] . ')', $valor['total']);
        }

        $chart4 = new Highchart();

        $chart4->chart->renderTo = "container4";
        $chart4->chart->plotBackgroundColor = null;
        $chart4->chart->plotBorderWidth = null;
        $chart4->chart->plotShadow = false;
        $chart4->title->text = "Productos menos vendido desde " . str_replace('-', '/', $_date_start_document) . " al " . str_replace('-', '/', $_date_end_document);

        $chart4->tooltip->formatter = new HighchartJsExpr(
            "function() {
    return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,0,',','.') +' %';}");

        $chart4->plotOptions->pie->allowPointSelect = 1;
        $chart4->plotOptions->pie->cursor = "pointer";
        $chart4->plotOptions->pie->dataLabels->enabled = 1;
        $chart4->plotOptions->pie->dataLabels->color = "#000000";
        $chart4->plotOptions->pie->dataLabels->connectorColor = "#000000";

        $chart4->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
            "function() {
    return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,0,',','.') +' %'; }");

        $chart4->series[] = array(
            'type' => "pie",
            'name' => "Productos",
            'data' => $data_values4
        );

        $chart4->credits->enabled = false;

        return view('admvisch.saleStatics.index')->with(['chart'=>$chart, 'chart2'=>$chart2, 'chart3'=>$chart3, 'chart4'=>$chart4, 
        '_date_start_document'=>$_date_start_document, '_date_end_document'=>$_date_end_document, 'title'=> $this->title, 'module'=>$this->module, 'error'=>$error]);
    }

}

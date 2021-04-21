<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Application\Helper;
use Application\HelperExcel;
use Illuminate\Http\Request;
use PHPExcel;

class ContactsController extends Controller
{
    private $title = 'Contacto';
    private $module = 'contacts';


    public function index(){


        // --------------------- FORM POST

        $_offices_id = array();

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document']))? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document']))? Helper::postValue('date_end_document') : date('d-m-Y');

        if(isset($_POST['offices_id']) && !empty($_POST['offices_id'])){
            foreach($_POST['offices_id'] AS $office_id){
                $_offices_id[] = $office_id;
            }
        }

        $offices = Contacts::join('offices', 'contacts.offices_id', '=', 'offices.id', 'LEFT OUTER')
            ->select([
                'offices.id',
                'offices.title'
            ])
            ->groupBy([
                'offices.id'
            ])
            ->orderBy('offices.title')
            ->get();

        //--------------------------------------------------------------------------------------------------------------------------------------

        return view('admvisch.contacts.index')->with(['title'=>$this->title, 'module'=>$this->module, 'offices'=>$offices, '_date_start_document'=>$_date_start_document, '_date_end_document'=>$_date_end_document, '_ofiices_id'=>$_offices_id]);
    }
    
    public function insert(Request $request){
        
        $post = array(
            
            'type' => Helper::postValue('type'),
            'name' => Helper::postValue('name'),
            'email' => Helper::postValue('email'),
            'phone' => Helper::postValue('phone'),
            'message' => Helper::postValue('message')
            
            );
 
        if($insert = Contacts::create($post)){
            
             session()->flash('error', 'success');
             return back();
        }else{
            session()->flash('error', 'failure');
             return back();
        }


    }

    public function documents(){

        $table_documents_body = array();
        $where = "";

        // --------------------- FORM POST

        $_date_start_document = (isset($_POST['date_start_document']) && !empty($_POST['date_start_document']))? Helper::postValue('date_start_document') : date('d-m-Y');
        $_date_end_document = (isset($_POST['date_end_document']) && !empty($_POST['date_end_document']))? Helper::postValue('date_end_document') : date('d-m-Y');

        $_offices_id = "";

        if(isset($_POST['offices_id']) && !empty($_POST['offices_id'])) {
            $_offices_id_array = explode('^', substr($_POST['offices_id'], 0, -1));
            if (count($_offices_id_array) > 0) {
                foreach ($_offices_id_array AS $office_id) {
                    $_offices_id .= "'" . $office_id . "',";
                }
                $_offices_id = substr($_offices_id, 0, -1);
            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        $contacts = Contacts::select([
                'contacts.id',
                'contacts.created_at',
                'contacts.type',
                'contacts.name'
            ])
            ->whereNotNull('contacts.id');

        if(!empty($_date_start_document)){
            $contacts = $contacts->whereRaw("DATE(contacts.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if(!empty($_date_end_documente)){
            $contacts = $contacts->whereRaw("DATE(contacts.created_at) <= '" . Helper::dateFormatSystem($_date_end_documente) . "'");
        }

        if(!empty($_offices_id)){
            $contacts = $contacts->whereIn("contacts.offices_id", $_offices_id);
        }

        $contacts = $contacts->orderBy('contacts.id', 'DESC')->get();

        if ($contacts->count() > 0) {
            foreach ($contacts AS $document) {

                $table_documents_body[] = array(
                    '<input type="checkbox" name="documents[]" value="' . $document->id . '">',
                    '<a style="cursor:pointer" class="show-compact-view" id="' . $document->id . '">Detalle Compacto</a>',
                    '<a href="' . URL_FRIENDLY_BASE . $this->module . '/view/' . $document->id . '" target="_blank"><i class="fa fa-list-ol" aria-hidden="true"></i> Ver Detalle</a>',
                    $document->id,
                    Helper::dateFormatUser($document->created_at),
                    $document->type,
                    $document->name,
                );
            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        echo json_encode(array(
            'data' => $table_documents_body,
        ));
    }

    public function view($id){

        $contact = Contacts::findOrFail($id);
        return view('admvisch.contacts.view')->with(['contact'=>$contact, 'title'=>$this->title, 'module'=>$this->module]);
    }

    public function viewCompact(){

        $id = Helper::postValue('id');

        $contact = Contacts::findOrFail($id);

        $data = '
        <div class="row">
            <div class="col-sm-12 invoice-left">
                <h3>CONTACTO ID. #' . $contact->id . '</h3>
                <span class="badge badge-success">' . Helper::formatDateToCompleteDateUser($contact->created_at, true) . '</span>
            </div>
        </div>

        <hr class="margin" />

        <div class="row">

            <div class="col-md-12">
                <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle</h4>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td width="20%" class="col-gris">Tipo:</td>
                        <td width="80%">' . $contact->type . '</td>
                    </tr>';

                    if(!empty($contact->offices_id)) {
                        $data .= '
                        <tr>
                            <td class="col-gris">Sucursal:</td>
                            <td>' . $contact->office->title . '</td>
                        </tr>';
                    }

                    $data .= '
                    <tr>
                        <td class="col-gris">Nombre:</td>
                        <td>' . $contact->name . '</td>
                    </tr>
                    <tr>
                        <td class="col-gris">Email:</td>
                        <td>' . $contact->email . '</td>
                    </tr>';

                    if(!empty($contact->phone)) {
                        $data .= '
                        <tr>
                            <td class="col-gris">Teléfono:</td>
                            <td>' . $contact->phone . '</td>
                        </tr>';
                    }

                    if(!empty($contact->mobile)) {
                        $data .= '
                        <tr>
                            <td class="col-gris">Celular:</td>
                            <td>' . $contact->mobile . '</td>
                        </tr>';
                    }

                    if(!empty($contact->city)) {
                        $data .= '
                        <tr>
                            <td class="col-gris">Ciudad:</td>
                            <td>' . $contact->city . '</td>
                        </tr>';
                    }

                    $data .= '
                    <tr>
                        <td valign="top" class="col-gris">Mensaje:</td>
                        <td valign="top">' . $contact->message . '</td>
                    </tr>
                </table>
            </div>

        </div>
        ';

        echo json_encode(array('data' => $data));
    }

    public function export($selected = false){

        $header = array();

        $header[] = "N°";
        $header[] = "Fecha";
        //$header[] = "Sucursal";
        $header[] = "Tipo";
        $header[] = "Nombre";
        $header[] = "Email";
        $header[] = "Teléfono";
        $header[] = "Celular";
        $header[] = "Ciudad";
        $header[] = "Mensaje";

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
        $_offices_id = "";

        if(isset($_POST['offices_id']) && !empty($_POST['offices_id'])){
            $_offices_id = implode(',', $_POST['offices_id']);
        }

        $contacts = Contacts::join('offices', 'contacts.offices_id', '=', 'offices.id', 'LEFT OUTER')
            ->select([
                'contacts.id',
                'contacts.created_at',
                'contacts.type',
                'contacts.name',
                'contacts.email',
                'contacts.phone',
                'contacts.mobile',
                'contacts.city',
                'contacts.message',
                'offices.title'
            ])
            ->whereNotNull('contacts.id');

        if(!empty($_date_start_document)){
            $contacts = $contacts->whereRaw("DATE(contacts.created_at) >= '" . Helper::dateFormatSystem($_date_start_document) . "'");
        }

        if(!empty($_date_end_documente)){
            $contacts = $contacts->whereRaw("DATE(contacts.created_at) <= '" . Helper::dateFormatSystem($_date_end_documente) . "'");
        }

        if(!empty($_offices_id)){
            $contacts = $contacts->whereIn("contacts.offices_id", $_offices_id);
        }

        if($selected == 1){

            $_documents = '';

            if(isset($_POST['documents']) && !empty($_POST['documents'])){
                if(count($_POST['documents']) > 0){
                    $_documents = implode(',', $_POST['documents']);
                }
            }

            if(!empty($_documents)){
                $contacts = $contacts->whereIn("contacts.id", $_documents);
            }
        }

        $contacts = $contacts->orderBy('contacts.id', 'DESC')->get();

        if ($contacts->count() > 0) {
            foreach ($contacts AS $document) {
                $data = array();

                $data[] = $document->id;
                $data[] = $document->created_at;
                //$data[] = $document->title;
                $data[] = $document->type;
                $data[] = $document->name;
                $data[] = $document->email;
                $data[] = $document->phone;
                $data[] = $document->mobile;
                $data[] = $document->city;
                $data[] = $document->message;

                $column = 0;
                foreach ($data as $item) {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($column, $counter_row, $item);
                    $column++;
                }

                $counter_row++;
            }
        }

        HelperExcel::applyExcelOutput($objPHPExcel, "contacto");

    }
}

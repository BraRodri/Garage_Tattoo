<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\ProductsArchives;

class ProductsArchivesController extends Controller
{
    private $title = 'Productos Certificaciones';
    private $parent_title = 'Tienda Virtual';
    private $module = 'productsArchives';
    private $table_name;


    public function index(){

        $products_id = Helper::postValue('id');

        $table_documents_body = '';

        $archives = Capsule::select("SELECT id, archive, expiration_date, active, updated_date, author FROM {$this->table_name} WHERE products_id = :products_id ORDER BY id DESC", [':products_id' => $products_id]);

        if(count($archives) > 0) {
            foreach ($archives AS $archive) {

                $status = $actions = $pdf = '';

                $class_status = ($archive->active == 1)? "success" : "default";
                $text_status = ($archive->active == 1)? "Activo" : "Inactivo";

                $status = '<a style="cursor: pointer;" class="change-status-archive" id="' . $archive->id . '"><span class="badge badge-' . $class_status . '">' . $text_status . '</span></a>';

                if($this->_acl->accessMenu($this->module, 'UPDATE')){
                    $actions .= '<a type="button" class="btn btn-sm btn-gold show-form-edit-archive" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" id="' .  $archive->id . '"><i class="fa fa fa-pencil-square-o"></i></a> ';
                }
                if($this->_acl->accessMenu($this->module, 'DELETE')){
                    $actions .= '<a type="button" class="btn btn-sm btn-danger delete-register-archive" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" id="' .  $archive->id . '"><i class="fa fa-trash-o"></i></a>';
                }

                if(!empty($archive->archive) && file_exists(UPLOAD_URL_ROOT . $this->module . DS . 'pdf' . DS . $archive->archive)) {
                    $pdf = '<a href="' . BASE_URL_ROOT . 'upload/' . $this->module . '/pdf/' . $archive->archive . '" target="_blank">Ver Archivo</a>';
                }

                $expiration_date = ($archive->expiration_date == '0000-00-00' || $archive->expiration_date == '')? '' : Helper::dateFormatUser($archive->expiration_date, false);

                $table_documents_body .= '<tr>';
                $table_documents_body .= '<td>' . $pdf . '</td>';
                $table_documents_body .= '<td>' . $expiration_date . '</td>';
                $table_documents_body .= '<td align="center">' . $status . '</td>';
                $table_documents_body .= '<td>' . Helper::dateFormatUser($archive->updated_date) . '</td>';
                $table_documents_body .= '<td>' . $archive->author . '</td>';
                $table_documents_body .= '<td>' . $actions . '</td>';
                $table_documents_body .= '</tr>';
            }
        } else {
            $table_documents_body .= '<tr>';
            $table_documents_body .= '<td colspan="6" align="center">No existen registros disponibles a visualizar</td>';
            $table_documents_body .= '</tr>';
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        echo json_encode(array(
            'data' => $table_documents_body
        ));
    }

    public function insert(){

        $products_id = Helper::postValue('products_id');

        $archive = '';
        if(isset($_FILES) && isset($_FILES['archive']) && $_FILES['archive']['size'] > 0) {
            $archive = Helper::uploadPdf($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'pdf', $inputName = 'archive');
            if ($archive == 'error') {
                echo json_encode(array('error' => 0, 'type' => 'upload'));
                exit();
            }
        }

        $expiration_date = (isset($_POST['expiration_date']) && !empty($_POST['expiration_date']))? Helper::dateFormatSystem(Helper::postValue('expiration_date')) : '';

        $post = array(
            'products_id' => $products_id,
            'archive' => $archive,
            'expiration_date' => $expiration_date,
            'recipient_email' => Helper::postValue('recipient_email'),
            'active' => Helper::postValue('active', 0),
            'author' => Helper::sessionSystemValue('user_name')
        );

        if ($insert = ProductsArchives::create($post))
        {
            $id = $insert->id;

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'INGRESO',
                    'identifier' => $id,
                    'detail' => 'Ingresó nueva certificación con ID N°' . $id . '.'
                ]);
            }

            echo json_encode(array('error' => 0, 'type' => 'success'));
        } else {
            echo json_encode(array('error' => 0, 'type' => 'failure'));
        }
    }

    public function edit($id){

        $archive = ProductsArchives::findOrFail($id);

        echo json_encode(array(
            'error' => 0,
            'id' => $archive->id,
            'expiration_date' => Helper::dateFormatUser($archive->expiration_date, false),
            'recipient_email' => $archive->recipient_email,
            'href' => BASE_URL_ROOT . 'upload/' . $this->module . '/pdf/' . $archive->archive,
            'active' => $archive->active,
        ));
    }

    public function update(){

        $id = Helper::postValue('id');

        $archive = '';
        if(isset($_FILES) && isset($_FILES['archive']) && $_FILES['archive']['size'] > 0) {
            $archive = Helper::uploadPdf($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'pdf', $inputName = 'archive');
            if ($archive == 'error') {
                echo json_encode(array('error' => 0, 'type' => 'upload'));
                exit();
            }
        }

        $expiration_date = (isset($_POST['expiration_date']) && !empty($_POST['expiration_date']))? Helper::dateFormatSystem(Helper::postValue('expiration_date')) : '';

        $post = array(
            'expiration_date' => $expiration_date,
            'recipient_email' => Helper::postValue('recipient_email'),
            'active' => Helper::postValue('active', 0),
            'author' => Helper::sessionSystemValue('user_name')
        );

        if(!empty($archive)){
            $post['archive'] = $archive;
        }

        if ($update = ProductsArchives::findOrFail($id)->update($post))
        {
            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ACTUALIZACION',
                    'identifier' => $id,
                    'detail' => 'Actualizó certificación con ID N°' . $id . '.'
                ]);
            }

            echo json_encode(array('error' => 0, 'type' => 'success'));
        } else {
            echo json_encode(array('error' => 0, 'type' => 'failure'));
        }
    }

    public function delete($id){

        $archive = ProductsArchives::findOrFail($id);

        $archive = $archive->archive;
        Helper::deleteArchive(UPLOAD_URL . $this->module . DS . 'pdf', $archive);

        if($delete = ProductsArchives::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó certificación con ID N°' . $id . '.'
                ]);
            }

            echo json_encode(array('error' => 0, 'type' => 'success'));
        } else {
            echo json_encode(array('error' => 0, 'type' => 'failure'));
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $archive = ProductsArchives::findOrFail($id);

            $active = ($archive->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($update = ProductsArchives::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

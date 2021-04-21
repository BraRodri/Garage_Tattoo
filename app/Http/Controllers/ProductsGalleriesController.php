<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\ProductsGalleries;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsGalleriesController extends Controller
{
    private $title = 'Productos Galerías';
    private $parent_title = 'Tienda Virtual';
    private $module = 'productsGalleries';
    private $table_name='products_galleries';


    public function index(){

        $products_id = Helper::postValue('id');

        $table_documents_body = '';

        $galleries = DB::select("SELECT id, image, active, updated_at, author FROM ".$this->table_name." WHERE products_id =".$products_id."  ORDER BY id DESC");

        if(count($galleries) > 0) {
            foreach ($galleries AS $gallery) {

                $status = $actions = $image = '';

                $class_status = ($gallery->active == 1)? "success" : "default";
                $text_status = ($gallery->active == 1)? "Activo" : "Inactivo";

                $status = '<a style="cursor: pointer;" class="change-status-gallery" id="' . $gallery->id . '"><span class="badge badge-' . $class_status . '">' . $text_status . '</span></a>';

             
                    $actions .= '<a type="button" class="btn btn-sm btn-gold show-form-edit-gallery" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" id="' .  $gallery->id . '"><i class="fa fa fa-pencil-square-o"></i></a> ';
               
               
                    $actions .= '<a type="button" class="btn btn-sm btn-danger delete-register-gallery" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" id="' .  $gallery->id . '"><i class="fa fa-trash-o"></i></a>';
               

                    $image = '<a href="' . BASE_URL_ROOT . 'files/' . $this->module . '/img/' . $gallery->image . '" data-fancybox="galeria"><img src="' . BASE_URL_ROOT . 'files/' . $this->module . '/img/' . $gallery->image . '" width="50" height="40" /></a>';
                

                $table_documents_body .= '<tr>';
                $table_documents_body .= '<td>' . $image . '</td>';
                $table_documents_body .= '<td align="center">' . $status . '</td>';
                $table_documents_body .= '<td>' . Helper::dateFormatUser($gallery->updated_at) . '</td>';
                $table_documents_body .= '<td>' . $gallery->author . '</td>';
                $table_documents_body .= '<td>' . $actions . '</td>';
                $table_documents_body .= '</tr>';
            }
        } else {
            $table_documents_body .= '<tr>';
            $table_documents_body .= '<td colspan="5" align="center">No existen registros disponibles a visualizar</td>';
            $table_documents_body .= '</tr>';
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        echo json_encode(array(
            'data' => $table_documents_body
        ));
    }

    public function insert(Request $request){

        $products_id = Helper::postValue('products_id');

        $image = '';
        if(isset($_FILES) && isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $image = Helper::uploadImage($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'img', $inputName = 'image');
            if ($image == 'error') {
                echo json_encode(array('error' => 0, 'type' => 'upload'));
                exit();
            }
        }
      
        $position = 0;
        $positions = DB::select('SELECT IFNULL((MAX(position) + 1), 0) AS position FROM ' . $this->table_name . ' WHERE products_id = :products_id', [':products_id' => $products_id]);
        if(count($positions) > 0){
            $position = current($positions);
            $position = ($position->position == 0)? 1 : $position->position;
        }

        $post = array(
            'products_id' => $products_id,
            'image' => $image,
            'position' => $position,
            'active' => Helper::postValue('active', 0),
            'author' => Auth::user()->name
        );

        if ($insert = ProductsGalleries::create($post))
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
                    'detail' => 'Ingresó nueva imagen con ID N°' . $id . '.'
                ]);
            }

            echo json_encode(array('error' => 0, 'type' => 'success'));
        } else {
            echo json_encode(array('error' => 0, 'type' => 'failure'));
        }
    }

    public function edit($id){

        $gallery = ProductsGalleries::findOrFail($id);

        echo json_encode(array(
            'error' => 0,
            'id' => $gallery->id,
            'href' => BASE_URL_ROOT . 'files/' . $this->module . '/img/' . $gallery->image,
            'src' => BASE_URL_ROOT . 'files/' . $this->module . '/img/' . $gallery->image,
            'active' => $gallery->active,
        ));
    }

    public function update(Request $request){

        $id = Helper::postValue('id');
        $producto=ProductsGalleries::findOrFail($id);
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $image = '';
        if(isset($_FILES) && isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $image = Helper::uploadImage($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'img', $inputName = 'image');
            if ($image == 'error') {
                echo json_encode(array('error' => 0, 'type' => 'upload'));
                exit();
            }
        }


        $post = array(
            'active' => Helper::postValue('active', 0),
            'author' => Auth::user()->name
        );

        if(!empty($url)){
            $post['image'] = $url;
        }

        if ($update = ProductsGalleries::findOrFail($id)->update($post))
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
                    'detail' => 'Actualizó imagen con ID N°' . $id . '.'
                ]);
            }

            echo json_encode(array('error' => 0, 'type' => 'success'));
        } else {
            echo json_encode(array('error' => 0, 'type' => 'failure'));
        }
    }

    public function delete($id){

        $gallery = ProductsGalleries::findOrFail($id);

        $position = $gallery->position;
        $productsgalleries = DB::select('SELECT id FROM ' . $this->table_name . ' WHERE position >= :position AND products_id = :products_id', [':position' => $position, ':products_id' => $gallery->products_id]);
        if(count($productsgalleries) > 0){
            foreach($productsgalleries AS $productgallery){
                DB::select('UPDATE ' . $this->table_name . ' SET position = position - 1 WHERE id = :id', [':id' => $productgallery->id]);
            }
        }

        $image = $gallery->image;
        Helper::deleteArchive(UPLOAD_URL . $this->module . DS . 'img', $image);

        if($delete = ProductsGalleries::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó imagen con ID N°' . $id . '.'
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

            $gallery = ProductsGalleries::findOrFail($id);

            $active = ($gallery->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($update = ProductsGalleries::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Messages;
use Application\Helper;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    private $title = 'Respuestas Correos';
    private $module = 'messages';
    private $type_message;


    public function index(){

        $this->_acl->accessGlobal($this->module);

        if($this->_request->getArguments() != null) {
            $errores = $this->_request->getArguments();
            $this->_view->error = current($errores);
        }

        $messages = Messages::orderBy('id', 'desc')->get();

        $this->_view->messages = $messages;
        $this->_view->load('index');
    }

    public function enter(){

        $this->_acl->accessGlobal($this->module, 'INSERT');

        if($this->_request->getArguments() != null) {
            $errores = $this->_request->getArguments();
            $this->_view->error = current($errores);
        }

        $this->_view->load('enter');
    }

    public function insert(){

        $this->_acl->accessGlobal($this->module, 'INSERT');

        $messages = Messages::where(['type' => Helper::postValue('type')])->get()->count();

        if($messages > 0){
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/enter/duplicate');
        } else {

            $post = array(
                'type' => Helper::postValue('type'),
                'header' => Helper::postValue('header'),
                'body' => Helper::postValue('body'),
                'footer' => Helper::postValue('footer'),
                'active' => Helper::postValue('active', 0),
                'created_date' => Helper::getDateSQL(),
                'updated_date' => Helper::getDateSQL(),
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($insert = Messages::create($post)) {

                $id_message = $insert->id;

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id_message,
                        'detail' => 'Ingresó nuevo mensaje de correo "' . Helper::postValue('type') . '" con ID N°' . $id_message . '.'
                    ]);
                }

                $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/success');
            } else {
                $this->redirect(URL_FRIENDLY_BASE . $this->module . '/enter/failure');
            }
        }
    }

    public function edit($id){

        $this->_acl->accessGlobal($this->module, 'UPDATE');

        if($this->_request->getArguments() != null) {
            $errores = $this->_request->getArguments();
            $this->_view->error = next($errores);
        }

        $message = Messages::findOrFail($id);

        $this->_view->message = $message;
        $this->_view->load('edit');
    }

    public function update(){

        $this->_acl->accessGlobal($this->module, 'UPDATE');

        $id = Helper::postValue('id');

        $messages = Messages::where('type', Helper::postValue('type'))->where('id', '<>', $id)->get()->count();

        if($messages > 0){
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/edit/' . $id . '/duplicate');
        } else {

            $post = array(
                'type' => Helper::postValue('type'),
                'header' => Helper::postValue('header'),
                'body' => Helper::postValue('body'),
                'footer' => Helper::postValue('footer'),
                'active' => Helper::postValue('active'),
                'author' => Helper::sessionSystemValue('user_name'),
            );

            if ($update = Messages::findOrFail($id)->update($post)) {

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'ACTUALIZACION',
                        'identifier' => $id,
                        'detail' => 'Actualizó mensaje de correo "' . Helper::postValue('type') . '" con ID N°' . $id . '.'
                    ]);
                }

                $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/success');
            } else {
                $this->redirect(URL_FRIENDLY_BASE . $this->module . '/edit/' . $id . '/failure');
            }
        }
    }

    public function delete($id){

        $this->_acl->accessGlobal($this->module, 'DELETE');

        $message = Messages::findOrFail($id);

        if($delete = Messages::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó mensaje de correo "' . $message->type . '" con ID N°' . $id . '.'
                ]);
            }

            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/success');
        } else {
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/failure');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $message = Messages::findOrFail($id);

            $active = ($message->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($update = Messages::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

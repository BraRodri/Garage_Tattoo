<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Responses;
use Application\Helper;
use Illuminate\Http\Request;

class ResponsesController extends Controller
{
    private $title = 'Respuestas Páginas';
    private $module = 'responses';


    public function index(){



        $responses = Responses::orderBy('id', 'desc')->get();

        return view('admvisch.responses.index')->with(['title'=>$this->title, 'module'=>$this->module, 'responses'=>$responses]);
    }

    public function enter(){

    

        return view('admvisch.responses.enter')->with(['title'=>$this->title, 'module'=>$this->module]);
    }

    public function insert(Request $request){


        $request->validate([

            
            'title'=> 'required',
            'type'=>'required'
            
        ]
            
        );
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $responses = Responses::where(['type' => Helper::postValue('type')])->get()->count();

        if($responses > 0){
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/enter/duplicate');
        } else {

            $post = array(
                'type' => Helper::postValue('type'),
                'title' => Helper::postValue('title'),
                'description1' => Helper::postValue('description1'),
                'description2' => Helper::postValue('description2'),
                'description3' => Helper::postValue('description3'),
                'description4' => Helper::postValue('description4'),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($insert = Responses::create($post)) {

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
                        'detail' => 'Ingresó nuevo respuesta "' . Helper::postValue('type') . '" con ID N°' . $id_message . '.'
                    ]);
                }

                session()->flash('error', 'success');
            return redirect()->route('responses');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('responses.enter');
            }
        }
    }

    public function edit($id){

       

        $response = Responses::findOrFail($id);

        return view('admvisch.responses.edit')->with(['response'=>$response, 'title'=>$this->title, 'module'=>$this->module]);
    }

    public function update(){


        $id = Helper::postValue('id');

        $responses = Responses::where('type', Helper::postValue('type'))->where('id', '<>', $id)->get()->count();
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        if($responses > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('responses.edit',$id);
        } else {

            $post = array(
                'type' => Helper::postValue('type'),
                'title' => Helper::postValue('title'),
                'description1' => Helper::postValue('description1'),
                'description2' => Helper::postValue('description2'),
                'description3' => Helper::postValue('description3'),
                'description4' => Helper::postValue('description4'),
                'active' => Helper::postValue('active'),
                'author' => $author
            );

            if ($update = Responses::findOrFail($id)->update($post)) {

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'ACTUALIZACION',
                        'identifier' => $id,
                        'detail' => 'Actualizó respuesta "' . Helper::postValue('type') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
            return redirect()->route('responses');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('responses.edit',$id);
            }
        }
    }

    public function delete($id){


        $response = Responses::findOrFail($id);

        if($delete = Responses::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó respuesta "' . $response->type . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('responses');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('responses');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $response = Responses::findOrFail($id);

            $active = ($response->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($update = Responses::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

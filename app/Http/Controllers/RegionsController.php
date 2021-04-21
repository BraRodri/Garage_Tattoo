<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Regions;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegionsController extends Controller
{
    private $title = 'Regiones';
    private $module = 'regions';

    public function index(){
   
        $regions = Regions::orderBy('position')->get();

        return view('admvisch.regions.index')->with(['title'=>$this->title, 'module'=>$this->module, 'regions'=>$regions]);
    }

    public function enter(){

        return view('admvisch.regions.enter')->with(['title'=>$this->title, 'module'=>$this->module ]);
    }

    public function insert(){

      

        $regions = Regions::where(['code' => Helper::postValue('code')])->get()->count();
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        if($regions > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('regions.enter');
        } else {

            $position = 0;
            $positions = DB::table($this->module)->max('position');
            $position = $positions + 1;

            $post = array(
                'code' => Helper::postValue('code'),
                'code_internal' => Helper::postValue('code_internal'),
                'description' => Helper::postValue('description'),
                'position' => $position,
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($insert = Regions::create($post))
            {
                $id = $insert;

                if(LOG_GENERATE === true){
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nueva región "' . Helper::postValue('description') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('regions');
            } else {
                session()->flash('error', 'failure');
            return redirect()->route('regions.enter');
            }
        }
    }

    public function edit($id){


        $region = Regions::findOrFail($id);
        return view('admvisch.regions.edit')->with(['title'=>$this->title, 'module'=>$this->module, 'region'=>$region]);

       
    }

    public function update(){


        $id = Helper::postValue('id');
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $regions = Regions::where('code', Helper::postValue('code'))->where('id', '<>', $id)->get()->count();

        if($regions > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('regions.edit',$id);
        } else {

            $post = array(
                'code' => Helper::postValue('code'),
                'code_internal' => Helper::postValue('code_internal'),
                'description' => Helper::postValue('description'),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($update = Regions::findOrFail($id)->update($post))
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
                        'detail' => 'Actualizó región "' . Helper::postValue('description') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
            return redirect()->route('regions');
            } else {
                session()->flash('error', 'failure');
            return redirect()->route('regions.edit',$id);
            }
        }
    }

    public function delete($id){


        $region = Regions::findOrFail($id);

        if($delete = Regions::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó región "' . $region->description . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
           return redirect()->route('regions');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('regions');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $region = Regions::findOrFail($id);

            $active = ($region->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Regions::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function orders(){

        $status = 0;
        $message = '';
        $counter = 1;

        if(isset($_POST['registers']) && !empty($_POST['registers']))
        {
            $registers = Helper::postValue('registers');

            if(count($registers) > 0)
            {
                foreach($registers AS $keyRegister => $valueRegister)
                {
                    $position = $counter;
                    $id = $valueRegister;

                    $post = array(
                        'position' => $position,
                        'author' => Auth::user()->name
                    );
                    Regions::findOrFail($id)->update($post);

                    $counter++;
                }

                $status = 1;
                $message = '<div class="alert alert-success"><strong>CORRECTO!</strong> Los registros fueron ordenados exitosamente.</div>';
            } else {
                $status = 1;
                $message = '<div class="alert alert-error"><strong>ERROR!</strong> No se ha logrado ordenar los registros.</div>';
            }
        }

        echo json_encode(array("status" => $status, "message" => $message));
    }
}

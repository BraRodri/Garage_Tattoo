<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Provinces;
use App\Models\Regions;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProvincesController extends Controller
{
    
    private $title = 'Provincias';
    private $module = 'provinces';

    public function index(){




        $provinces = Provinces::all()->sortByDesc('id');

        return view('admvisch.provinces.index')->with(['title'=>$this->title, 'module'=>$this->module, 'provinces'=>$provinces]);
    }

    public function enter(){

       

        $regions = Regions::orderBy('position')->get();

        return view('admvisch.provinces.enter')->with(['title'=>$this->title, 'module'=>$this->module, 'regions'=>$regions]);

    }

    public function insert(){


        $provinces = Provinces::where(['code' => Helper::postValue('code')])->get()->count();
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        if($provinces > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('provinces.enter');
        } else {

            $post = array(
                'parent_code' => Helper::postValue('parent_code'),
                'code' => Helper::postValue('code'),
                'description' => Helper::postValue('description'),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($insert = Provinces::create($post))
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
                        'detail' => 'Ingresó nueva provincia "' . Helper::postValue('description') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('provinces');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('provinces.enter');
            }
        }
    }

    public function edit($id){


        $regions = Regions::orderBy('position')->get();
        $province = Provinces::findOrFail($id);

        return view('admvisch.provinces.edit')->with(['title'=>$this->title, 'module'=>$this->module, 'regions'=>$regions, 'province'=>$province]);
    }

    public function update(){



        $id = Helper::postValue('id');
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $provinces = Provinces::where('code', Helper::postValue('code'))->where('id', '<>', $id)->get()->count();

        if($provinces > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('provinces.edit',$id);
        } else {

            $post = array(
                'parent_code' => Helper::postValue('parent_code'),
                'code' => Helper::postValue('code'),
                'description' => Helper::postValue('description'),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($update = Provinces::findOrFail($id)->update($post))
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
                        'detail' => 'Actualizó provincia "' . Helper::postValue('description') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
            return redirect()->route('provinces');
            } else {
                session()->flash('error', 'failure');
            return redirect()->route('provinces.edit',$id);
            }
        }
    }

    public function delete($id){

        $province = Provinces::findOrFail($id);

        if($delete = Provinces::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó provincia "' . $province->description . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('provinces');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('provinces');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $province = Provinces::findOrFail($id);

            $active = ($province->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Provinces::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

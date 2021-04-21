<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\Log;
use App\Models\Provinces;
use App\Models\Regions;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationsController extends Controller
{
    private $title = 'Comunas';
    private $module = 'locations';

    public function index(){

       
        $locations = Locations::all()->sortByDesc('id');
        return view('admvisch.locations.index')->with(['title'=>$this->title, 'module'=>$this->module, 'locations'=>$locations]);
    }

    public function enter(){

        $regions = Regions::orderBy('position')->get();

        return view('admvisch.locations.enter')->with(['title'=>$this->title, 'module'=>$this->module, 'regions'=>$regions]);
    }

    public function insert(){

        $locations = Locations::where(['code' => Helper::postValue('code')])->get()->count();
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        if($locations > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('locations.enter');
        } else {

            $post = array(
                'parent_code' => Helper::postValue('parent_code'),
                'code' => Helper::postValue('code'),
                'description' => Helper::postValue('description'),
                'shipping_cost' => Helper::postValue('shipping_cost', 0),
                'active_shipping' => Helper::postValue('active_shipping', 0),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($insert = Locations::create($post))
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
                        'detail' => 'Ingresó nueva comuna "' . Helper::postValue('description') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('locations');
            } else {
                session()->flash('error', 'failure');
            return redirect()->route('locations.enter');
            }
        }
    }

    public function edit($id){

        
        $location = Locations::findOrFail($id);

        $provinces = Provinces::where(['parent_code' => $location->province->region->code])->orderBy('description')->get();

        $regions = Regions::orderBy('position')->get();

        return view('admvisch.locations.edit')->with(['title'=>$this->title, 'module'=>$this->module, 'regions'=>$regions, 'provinces'=>$provinces, 'location'=>$location]);
    }

    public function update(){


        $id = Helper::postValue('id');
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $locations = Locations::where('code', Helper::postValue('code'))->where('id', '<>', $id)->get()->count();

        if($locations > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('locations.edit',$id);
        } else {

            $post = array(
                'parent_code' => Helper::postValue('parent_code'),
                'code' => Helper::postValue('code'),
                'description' => Helper::postValue('description'),
                'shipping_cost' => Helper::postValue('shipping_cost', 0),
                'active_shipping' => Helper::postValue('active_shipping', 0),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($update = Locations::findOrFail($id)->update($post))
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
                        'detail' => 'Actualizó comuna "' . Helper::postValue('description') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
            return redirect()->route('locations');
            } else {
                session()->flash('error', 'failure');
            return redirect()->route('locations.edit',$id);
            }
        }
    }

    public function delete($id){


        $location = Locations::findOrFail($id);

        if($delete = Locations::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó comuna "' . $location->description . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('locations');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('locations');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $location = Locations::findOrFail($id);

            $active = ($location->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Locations::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function provinces($code){

        $option_provinces  = '';
        $option_provinces .= '<option value="">Seleccionar</option>';

        $provinces = Provinces::where(['parent_code' => $code])->orderBy('description')->get();

        if(count($provinces) > 0) {
            foreach ($provinces AS $province) {
                $option_provinces .= '<option value="' . $province->code . '">' . $province->description . '</option>';
            }
        }

        echo $option_provinces;
    }
}

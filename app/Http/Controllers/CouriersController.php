<?php

namespace App\Http\Controllers;

use App\Models\Couriers;
use App\Models\Log;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CouriersController extends Controller
{
    private $title = 'Empresas de Despachos';
    private $module = 'couriers';
    private $image_description = '1900 x 330';


    public function index()
    {
      

        $couriers = Couriers::orderBy('position')->get();
        return view('admvisch.couriers.index')->with(['title'=>$this->title, 'module'=>$this->module,'couriers'=>$couriers, 'image_description'=>$this->image_description]);
    }

    public function enter(){


        return view('admvisch.couriers.enter')->with(['title'=>$this->title, 'module'=>$this->module,'image_description'=>$this->image_description]);

    }

    public function insert(Request $request){


        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';
/*
        $image=$request->file('image')->store('public/imagenes');
        $url=Storage::url($image);
*/
        $position = 0;
        $positions = DB::table($this->module)->max('position');
        $position = $positions + 1;

        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'image' => '',
            'link' => Helper::postValue('link'),
            'target' => Helper::postValue('target'),
            'position' => $position,
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if($insert = Couriers::create($post))
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
                    'detail' => 'Ingresó nuevo courier "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('couriers');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('couriers.enter');
    }
}

    public function edit($id){

        $courier = Couriers::findOrFail($id);

        return view('admvisch.couriers.edit')->with(['title'=>$this->title, 'module'=>$this->module,'courier'=>$courier, 'image_description'=>$this->image_description]);

    }

    public function update(Request $request){


        $id = Helper::postValue('id');
        $courier=Couriers::findOrFail($id);
/*
        if($request->hasFile('image')){
            // busco la imagen anterior y la elimino

                $url_anterior= str_replace('storage','public', $courier->image);
                Storage::delete($url_anterior);

                //agrego la nueva imagen
            $image=$request->file('image')->store('public/imagenes');
            $url=Storage::url($image);
        }
        */

        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'link' => Helper::postValue('link'),
            'target' => Helper::postValue('target'),
            'active' => Helper::postValue('active', 0),
            'author' => Helper::sessionSystemValue('user_name')
        );

        if(!empty($url)){
            $post['image'] = $url;
        }

        if ($update = Couriers::findOrFail($id)->update($post))
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
                    'detail' => 'Actualizó courier "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('couriers');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('couriers.edit',$id);
        }
    }

    public function delete($id){


        $slider = Couriers::findOrFail($id);
/*
        $image = $slider->image;
        $url= str_replace('storage','public', $slider->image);
        Storage::delete($url);
        */
        $position = $slider->position;
        $couriers = DB::select('SELECT id FROM ' . $this->module . ' WHERE position >= :position', [':position' => $position]);
        if(count($couriers) > 0){
            foreach($couriers AS $slider){
                DB::select('UPDATE ' . $this->module . ' SET position = position - 1 WHERE id = :id', [':id' => $slider->id]);
            }
        }

        if($delete = Couriers::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó courier "' . $slider->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
           return redirect()->route('couriers');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('couriers');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $slider = Couriers::findOrFail($id);

            $active = ($slider->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($update = Couriers::findOrFail($id)->update($post))
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
                    Couriers::findOrFail($id)->update($post);

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

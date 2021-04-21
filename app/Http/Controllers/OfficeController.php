<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Office;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfficeController extends Controller
{
    private $title = 'Sucursales';
    private $module = 'offices';
    private $image_description = '280 x 180';

    public function index(){
        $offices = Office::orderBy('position')->get();

        return view('admvisch.offices.index')->with(['title'=>$this->title, 'module'=>$this->module, 'offices'=>$offices, 'image_description'=>$this->image_description]);
    }

    public function enter(){

        
        return view('admvisch.offices.enter')->with(['title'=>$this->title, 'module'=>$this->module, 'image_description'=>$this->image_description]);
    }

    public function insert(Request $request){

        $image=$request->file('image')->store('public/imagenes');
        $url=Storage::url($image);

        $position = 0;
        $positions = DB::table($this->module)->max('position');
        $position = $positions + 1;
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'address' => Helper::postValue('address'),
            'city' => Helper::postValue('city'),
            'phone' => Helper::postValue('phone'),
            'fax' => Helper::postValue('fax'),
            'email' => Helper::postValue('email'),
            'horary' => Helper::postValue('horary'),
            'map' => Helper::postValue('map'),
            'image' => $url,
            'position' => $position,
            'active' => Helper::postValue('active', 0),
            'code_webpay' => Helper::postValue('code_webpay'),
            'author' => $author
        );

        if ($insert = Office::create($post))
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
                    'detail' => 'Ingresó nueva sucursal "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('offices');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('offices.enter');
        }
    }

    public function edit($id){


        $office = Office::findOrFail($id);

        return view('admvisch.offices.edit')->with(['title'=>$this->title, 'module'=>$this->module, 'office'=>$office, 'image_description'=>$this->image_description]);
    }

    public function update(Request $request){

        $id = $request->id;
        $office=Office::findOrFail($id);

      

        if($request->hasFile('image')){
            // busco la imagen anterior y la elimino

                $url_anterior= str_replace('storage','public', $office->image);
                Storage::delete($url_anterior);

                //agrego la nueva imagen
            $image=$request->file('image')->store('public/imagenes');
            $url=Storage::url($image);
        }

        $id = Helper::postValue('id');

        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';


        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'address' => Helper::postValue('address'),
            'city' => Helper::postValue('city'),
            'phone' => Helper::postValue('phone'),
            'fax' => Helper::postValue('fax'),
            'email' => Helper::postValue('email'),
            'horary' => Helper::postValue('horary'),
            'map' => Helper::postValue('map'),
            'active' => Helper::postValue('active', 0),
            'code_webpay' => Helper::postValue('code_webpay'),
            'author' => $author
        );

        if(!empty($url)){
            $post['image'] = $url;
        }

        if ($update = Office::findOrFail($id)->update($post))
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
                    'detail' => 'Actualizó sucursal "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('offices');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('offices.edit',$id);
        }
    }

    public function delete($id){


        $office = Office::findOrFail($id);

        $image = $office->image;
        $url= str_replace('storage','public', $office->image);
        Storage::delete($url);

        $position = $office->position;
        $offices = DB::select('SELECT id FROM ' . $this->module . ' WHERE position >= :position', [':position' => $position]);
        if(count($offices) > 0){
            foreach($offices AS $office){
                DB::select('UPDATE ' . $this->module . ' SET position = position - 1 WHERE id = :id', [':id' => $office->id]);
            }
        }

        if($delete = Office::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó sucursal "' . $office['title'] . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
           return redirect()->route('offices');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('offices');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $office = Office::findOrFail($id);

            $active = ($office->active == 0)? 1 : 0;
        

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Office::findOrFail($id)->update($post))
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
                    Office::findOrFail($id)->update($post);

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

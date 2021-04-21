<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\SlidersPartners;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SlidersPartnersController extends Controller
{
    private $title = 'Representaciones';
    private $module = 'slidersPartners';
    private $table_name = 'sliders_partners';
    private $image_description = '150 x 100';

    public function index()
    {
  

        $sliders = SlidersPartners::orderBy('position')->get();

        return view('admvisch.slidersPartners.index')->with(['title'=>$this->title, 'module' => $this->module, 'sliders' => $sliders ]);
    }

    public function enter(){

        return view('admvisch.slidersPartners.enter')->with(['title'=>$this->title, 'module'=>$this->module, 'image_description'=>$this->image_description]);
    }

    public function insert(Request $request){

        $request->validate([

            'image'=> 'required|image|max:2048',
            'title'=> 'required',
            'link'=> 'required',
            'target'=> 'required',
            'active'=> 'required'
        ]
            
        );
     

        $image=$request->file('image')->store('public/imagenes');
        $url=Storage::url($image);

        $position = 0;
        $positions = DB::table($this->table_name)->max('position');
        $position = $positions + 1;
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';
        $post = array(
            'location' => Helper::postValue('location'),
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'image' => $url,
            'link' => Helper::postValue('link'),
            'target' => Helper::postValue('target'),
            'position' => $position,
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if($insert = SlidersPartners::create($post))
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
                    'detail' => 'Ingresó nuevo slider clientes "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('slidersPartners');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('slidersPartners.enter');
        }
    }

    public function edit($id){

        

        $slider = SlidersPartners::findOrFail($id);

        return view('admvisch.slidersPartners.edit')->with(['slider'=>$slider, 'title'=>$this->title, 'module'=>$this->module, 'image_description'=>$this->image_description]);
    }

    public function update(Request $request){

        $id = $request->id;
        $slider=SlidersPartners::findOrFail($id);

      

        if($request->hasFile('image')){
            // busco la imagen anterior y la elimino

                $url_anterior= str_replace('storage','public', $slider->image);
                Storage::delete($url_anterior);

                //agrego la nueva imagen
            $image=$request->file('image')->store('public/imagenes');
            $url=Storage::url($image);
        }

        $id = Helper::postValue('id');

        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $post = array(
            'location' => Helper::postValue('location'),
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'link' => Helper::postValue('link'),
            'target' => Helper::postValue('target'),
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if(!empty($rul)){
            $post['image'] = $url;
        }

        if ($update = SlidersPartners::findOrFail($id)->update($post))
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
                    'detail' => 'Actualizó slider clientes "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('slidersPartners');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('slidersPartners.edit',$id);
        }
    }

    public function delete($id){


        $slider = SlidersPartners::findOrFail($id);

        $image = $slider->image;
        $url= str_replace('storage','public', $slider->image);
        Storage::delete($url);

        $position = $slider->position;
        $sliders = DB::select('SELECT id FROM ' . $this->table_name . ' WHERE position >= :position', [':position' => $position]);
        if(count($sliders) > 0){
            foreach($sliders AS $slider){
                DB::select('UPDATE ' . $this->table_name . ' SET position = position - 1 WHERE id = :id', [':id' => $slider->id]);
            }
        }

        if($delete = SlidersPartners::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó slider clientes "' . $slider->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
           return redirect()->route('slidersPartners');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('slidersPartners');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $slider = SlidersPartners::findOrFail($id);

            $active = ($slider->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = SlidersPartners::findOrFail($id)->update($post))
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
                    SlidersPartners::findOrFail($id)->update($post);

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

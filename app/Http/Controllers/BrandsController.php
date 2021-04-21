<?php
namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Log;
use Application\Helper;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrandsController extends Controller {

    private $title = 'Marcas';
    private $parent_title = 'Tienda Virtual';
    private $module = 'brands';


    public function index(){

       

        $brands = Brands::orderBy('id', 'desc')->get();

       
        return view('admvisch.brands.index')->with(['title'=>$this->title, 'module'=>$this->module, 'parent_title'=>$this->parent_title,'brands'=>$brands]);
    }

    public function enter(){


        return view('admvisch.brands.enter')->with(['title'=>$this->title, 'module'=>$this->module, 'parent_title'=>$this->parent_title]);
    }

    public function insert(){


        $brands = Brands::where(['title' => Helper::postValue('title')])->get()->count();
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        if($brands > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('brands.enter');
        } 

            $position = 0;
            $positions = DB::table($this->module)->max('position');
            $position = $positions + 1;

            $post = array(
                'title' => Helper::postValue('title'),
                'description' => Helper::postValue('description'),
                'link' => Helper::postValue('link'),
                'image'=>'',
                'target' => Helper::postValue('target'),
                'position' => $position,
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if($insert = Brands::create($post))
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
                        'detail' => 'Ingresó nueva marca "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('brands');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('brands.enter');
            }
        }
    

    public function edit($id){

        $brand = Brands::findOrFail($id);

       
        return view('admvisch.brands.edit')->with(['title'=>$this->title, 'module'=>$this->module, 'parent_title'=>$this->parent_title,'brand'=>$brand]);
    }

    public function update(){


        $id = Helper::postValue('id');
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $brands = Brands::where('title', Helper::postValue('title'))->where('id', '<>', $id)->get()->count();

        if($brands > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('brands.edit',$id);
        } else {

            $image = '';
            
            }

            $post = array(
                'title' => Helper::postValue('title'),
                'description' => Helper::postValue('description'),
                'link' => Helper::postValue('link'),
                'target' => Helper::postValue('target'),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if(!empty($image)){
                $post['image'] = $image;
            }

            if ($update = Brands::findOrFail($id)->update($post))
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
                        'detail' => 'Actualizó marca "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('brands');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('brands.edit',$id);
            }
        }
    

    public function delete($id){

        $brand = Brands::findOrFail($id);


        $position = $brand->position;
        $brands = DB::select('SELECT id FROM ' . $this->module . ' WHERE position >= :position', [':position' => $position]);
        if(count($brands) > 0){
            foreach($brands AS $brand){
                DB::select('UPDATE ' . $this->module . ' SET position = position - 1 WHERE id = :id', [':id' => $brand->id]);
            }
        }

        if($delete = Brands::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó marca "' . $brand->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('brands');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('brands');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $brand = Brands::findOrFail($id);

            $active = ($brand->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Brands::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

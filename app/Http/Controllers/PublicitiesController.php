<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Application\Helper;
use App\Models\Publicities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Capsule\Manager as Capsule;

class PublicitiesController extends Controller
{
    private $title = 'Alerta Promociones';
    private $module = 'publicities';
    private $image_description = '410 x 181';
    private $options = array(
        'DESKTOP' => 'Desktop',
        'MOVIL' => 'Movil',
    );

    public function index()
    {

        $publicities = Publicities::orderBy('position')->get();
        return view('admvisch.publicities.index')->with(['publicities' => $publicities, 'title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description, 'options' => $this->options]);
    }

    public function enter()
    {


        return view('admvisch.publicities.enter')->with(['title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description, 'options' => $this->options]);
    }

    public function insert(Request $request)
    {

        $request->validate(
            [
                'location' => 'required',
                'image' => 'required|image|max:2048',
                'title' => 'required',
                'active' => 'required'
            ]
        );

        $image = $request->file('image')->store('public/imagenes');
        $url = Storage::url($image);

        $position = 0;
        $positions = DB::table($this->module)->max('position');
        $position = $positions + 1;
        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';
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

        if ($insert = Publicities::create($post)) {
            $id = $insert->id;

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'INGRESO',
                    'identifier' => $id,
                    'detail' => 'Ingresó nueva publicidad "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('publicities');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('publicities.enter');
        }
    }

    public function edit($id)
    {


        $publicity = Publicities::findOrFail($id);

        return view('admvisch.publicities.edit')->with(['publicity' => $publicity, 'title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description, 'options' => $this->options]);
    }

    public function update(Request $request)
    {

        $id = $request->id;
        $publicity = Publicities::findOrFail($id);

        if ($request->hasFile('image')) {
            // busco la imagen anterior y la elimino

            $url_anterior = str_replace('storage', 'public', $publicity->image);
            Storage::delete($url_anterior);

            //agrego la nueva imagen
            $image = $request->file('image')->store('public/imagenes');
            $url = Storage::url($image);
        }

        $id = Helper::postValue('id');

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';


        $post = array(
            'location' => Helper::postValue('location'),
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'link' => Helper::postValue('link'),
            'target' => Helper::postValue('target'),
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if (!empty($url)) {
            $post['image'] = $url;
        }

        if ($update = Publicities::findOrFail($id)->update($post)) {
            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ACTUALIZACION',
                    'identifier' => $id,
                    'detail' => 'Actualizó publicidad "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('publicities');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('publicities.edit', $id);
        }
    }

    public function delete($id)
    {


        $publicity = Publicities::findOrFail($id);

        $image = $publicity->image;
        $url = str_replace('storage', 'public', $publicity->image);
        Storage::delete($url);

        $position = $publicity->position;
        $publicities = DB::select('SELECT id FROM ' . $this->module . ' WHERE position >= :position', [':position' => $position]);

        foreach ($publicities as $publicity) {
            DB::select('UPDATE ' . $this->module . ' SET position = position - 1 WHERE id = :id', [':id' => $publicity->id]);
        }


        if ($delete = Publicities::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó publicidad "' . $publicity->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('publicities');
        } else {
            session()->flash('error', 'success');
            return redirect()->route('publicities');
        }
    }

    public function status()
    {

        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $publicity = Publicities::findOrFail($id);

            $active = ($publicity->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Publicities::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "badge-success" : "badge-default";
                $text_status = ($active == 1) ? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function orders()
    {

        $status = 0;
        $message = '';
        $counter = 1;

        if (isset($_POST['registers']) && !empty($_POST['registers'])) {
            $registers = Helper::postValue('registers');

            if (count($registers) > 0) {
                foreach ($registers as $keyRegister => $valueRegister) {
                    $position = $counter;
                    $id = $valueRegister;

                    $post = array(
                        'position' => $position,
                        'author' => Helper::sessionSystemValue('user_name')
                    );
                    Publicities::findOrFail($id)->update($post);

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

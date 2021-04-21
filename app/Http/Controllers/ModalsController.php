<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Modals;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\HttpFoundation\Session\Session;

class ModalsController extends Controller
{
    private $title = 'Avisos';
    private $module = 'modals';
    private $image_description = '700 x 700';



    public function index()
    {
        $modals = Modals::orderBy('position')->get();
        return view('admvisch.modals.index')->with(['modals' => $modals, 'title' => $this->title, 'module' => $this->module]);
    }

    public function enter()
    {
        return view('admvisch.modals.enter')->with(['title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description]);
    }

    public function insert(Request $request)
    {


        $request->validate(
            [

                'image' => 'required|image|max:2048',
                'title' => 'required',
                'target' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'active' => 'required'
            ]

        );
        $image = $request->file('image')->store('public/imagenes');
        $url = Storage::url($image);


        $position = 0;
        $positions = DB::table($this->module)->max('position');
        $position = $positions + 1;

        $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
        $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

        $start_hour = (isset($_POST['start_hour']) && !empty($_POST['start_hour'])) ? Helper::postValue('start_hour') : '';
        $end_hour = (isset($_POST['end_hour']) && !empty($_POST['end_hour'])) ? Helper::postValue('end_hour') : '';


        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';
        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'image' => $url,
            'link' => Helper::postValue('link'),
            'target' => Helper::postValue('target'),
            'position' => $position,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if ($insert = Modals::create($post)) {
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
                    'detail' => 'Ingresó nuevo aviso "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('modals');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('modals.enter');
        }
    }

    public function edit($id)
    {



        $modal = Modals::findOrFail($id);

        $explode_start_date = explode(' ', $modal->start_date);
        $explode_end_date = explode(' ', $modal->end_date);

        $modal->start_hour = end($explode_start_date);
        $modal->end_hour = end($explode_end_date);


        $modal->start_date = ($modal->start_date == '0000-00-00' || $modal->start_date == '') ? '' : Helper::dateFormatUser($modal->start_date, false);
        $modal->end_date = ($modal->end_date == '0000-00-00'  || $modal->end_date == '') ? '' : Helper::dateFormatUser($modal->end_date, false);


        return view('admvisch.modals.edit')->with(['modal' => $modal, 'title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description]);
    }

    public function update(Request $request)
    {


        $id = $request->id;
        $modal = Modals::findOrFail($id);



        if ($request->hasFile('image')) {
            // busco la imagen anterior y la elimino

            $url_anterior = str_replace('storage', 'public', $modal->image);
            Storage::delete($url_anterior);

            //agrego la nueva imagen
            $image = $request->file('image')->store('public/imagenes');
            $url = Storage::url($image);
        }



        $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
        $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

        $start_hour = (isset($_POST['start_hour']) && !empty($_POST['start_hour'])) ? Helper::postValue('start_hour') : '';
        $end_hour = (isset($_POST['end_hour']) && !empty($_POST['end_hour'])) ? Helper::postValue('end_hour') : '';

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';
        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description'),
            'link' => Helper::postValue('link'),
            'target' => Helper::postValue('target'),
            'start_date' => $start_date . ' ' . $start_hour,
            'end_date' => $end_date . ' ' . $end_hour,
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if (!empty($url)) {
            $post['image'] = $url;
        }

        if ($update = Modals::findOrFail($id)->update($post)) {
            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ACTUALIZACION',
                    'identifier' => $id,
                    'detail' => 'Actualizó aviso "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('modals');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('modals.edit', $id);
        }
    }

    public function delete($id)
    {


        $modal = Modals::findOrFail($id);

        $image = $modal->image;
        $url = str_replace('storage', 'public', $modal->image);
        Storage::delete($url);
        $position = $modal->position;
        $modals = DB::select('SELECT id FROM ' . $this->module . ' WHERE position >= :position', [':position' => $position]);
        if (count($modals) > 0) {
            foreach ($modals as $modal) {
                DB::select('UPDATE ' . $this->module . ' SET position = position - 1 WHERE id = :id', [':id' => $modal->id]);
            }
        }

        if ($delete = Modals::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó aviso "' . $modal->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('modals');
        } else {

            session()->flash('error', 'failure');
            return redirect()->route('modals');
        }
    }

    public function status()
    {

        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $modal = Modals::findOrFail($id);

            $active = ($modal->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Modals::findOrFail($id)->update($post)) {
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
                    Modals::findOrFail($id)->update($post);

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

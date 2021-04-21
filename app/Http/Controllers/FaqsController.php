<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Faqs;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;

class FaqsController extends Controller
{
    private $title = 'Preguntas Frecuentes';
    private $module = 'faqs';
    private $table_name = 'faqs';


    public function index()
    {
        $faqs = Faqs::orderBy('position')->get();

        return view('admvisch.faqs.index')->with(['title' => $this->title, 'module' => $this->module, 'faqs' => $faqs]);
    }

    public function enter()
    {
        return view('admvisch.faqs.enter')->with(['title' => $this->title, 'module' => $this->module]);
    }

    public function insert(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'description1' => 'required'
        ]);

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $position = 0;
        $positions = DB::table($this->table_name)->max('position');
        $position = $positions + 1;

        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description1'),
            'position' => $position,
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if ($insert = Faqs::create($post)) {

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
            return redirect()->route('faqs');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('faqs.enter');
        }
    }

    public function edit($id)
    {
        $faqs = Faqs::findOrFail($id);

        return view('admvisch.faqs.edit')->with(['faqs' => $faqs, 'title' => $this->title, 'module' => $this->module]);
    }

    public function update()
    {

        $id = Helper::postValue('id');

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description1'),
            'active' => Helper::postValue('active'),
            'author' => $author
        );

        if ($update = Faqs::findOrFail($id)->update($post)) {

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
            return redirect()->route('faqs');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('faqs.edit', $id);
        }
    }

    public function delete($id)
    {

        $faqs = Faqs::findOrFail($id);

        if ($delete = Faqs::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó respuesta "' . $faqs->type . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('faqs');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('faqs');
        }
    }

    public function status()
    {

        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $faqs = Faqs::findOrFail($id);

            $active = ($faqs->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($update = Faqs::findOrFail($id)->update($post)) {
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
                    Faqs::findOrFail($id)->update($post);

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

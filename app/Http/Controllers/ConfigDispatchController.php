<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Application\Helper;
use Illuminate\Http\Request;
use App\Models\ConfigDispatch;
use App\Models\Configurations;
use Auth;

class ConfigDispatchController extends Controller
{
    //
    private $title = 'Configuración de despacho.';
    private $parent_title = 'Configuración de despacho.';
    private $module = 'configDispatch';

    public function index()
    {
        $configuration = Configurations::findOrFail(1);
        $configDispatchs = ConfigDispatch::all();
        return view('admvisch.dispatch.index')
            ->with([
                'title' => $this->title,
                'parent_title' => $this->parent_title,
                'module' => $this->module,
                'configuration' => $configuration,
                'configDispatchs' => $configDispatchs
            ]);
    }

    public function enter()
    {
        return view('admvisch.dispatch.enter')->with(['title' => $this->title, 'module' => $this->module]);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'description1' => 'required',
            'price' => 'required',
            'active' => 'required'
        ]);

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'description' => Helper::postValue('description1'),
            'price' => Helper::postValue('price'),
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if ($insert = ConfigDispatch::create($post)) {
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
                    'detail' => 'Ingresó nueva entrada  "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('config.dispatch');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('config.dispatch.enter');
        }
    }

    public function edit($id)
    {
        $configDispatch = ConfigDispatch::findOrFail($id);
        return view('admvisch.dispatch.edit')->with(['configDispatch' => $configDispatch, 'title' => $this->title, 'module' => $this->module]);
    }

    public function update(Request $request)
    {
        $id = Helper::postValue('id');
        $configDispatch = ConfigDispatch::findOrFail($id);

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'description' => Helper::postValue('description1'),
            'price' => Helper::postValue('price'),
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if ($update = ConfigDispatch::findOrFail($id)->update($post)) {
            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ACTUALIZACION',
                    'identifier' => $id,
                    'detail' => 'Actualizó entrada "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('config.dispatch');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('config.dispatch.edit', $id);
        }
    }

    public function delete($id)
    {
        //
        $configDispatch = ConfigDispatch::findOrFail($id);

        if ($delete = ConfigDispatch::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó respuesta "' . $configDispatch->id . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('config.dispatch');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('config.dispatch');
        }
    }

    public function status()
    {
        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $configDispatch = ConfigDispatch::findOrFail($id);

            $active = ($configDispatch->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = ConfigDispatch::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "badge-success" : "badge-default";
                $text_status = ($active == 1) ? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function statusRetiroTienda()
    {
        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $configuration = Configurations::findOrFail($id);

            $active = ($configuration->office_shipping_active == 0) ? 1 : 0;

            $post = array(
                'office_shipping_active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Configurations::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "btn-success" : "btn-danger";
                $text_status = ($active == 1) ? "Activado" : "Desactivado";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

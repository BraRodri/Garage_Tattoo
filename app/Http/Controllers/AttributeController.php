<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Application\Helper;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $title = 'Atributos';
    private $module = 'attribute';
    private $table_name = 'attribute';

    public function index()
    {
        //
        $attributes = Attribute::orderBy('created_at')->get();
        return view('admvisch.attributes.index')->with(['title' => $this->title, 'module' => $this->module, 'attributes' => $attributes]);
    }

    public function enter()
    {
        //
        return view('admvisch.attributes.enter')->with(['title' => $this->title, 'module' => $this->module]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'description1' => 'required',
            'valor' => 'required'
        ]);

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description1'),
            'values' => Helper::postValue('valor'),
            'type' => Helper::postValue('tipo'),
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if ($insert = Attribute::create($post)) {

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
            return redirect()->route('attributes');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('attributes.enter');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $attribute = Attribute::findOrFail($id);
        return view('admvisch.attributes.edit')->with(['attribute' => $attribute, 'title' => $this->title, 'module' => $this->module]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        //
        $id = Helper::postValue('id');

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description1'),
            'values' => Helper::postValue('valor'),
            'type' => Helper::postValue('tipo'),
            'active' => Helper::postValue('active'),
            'author' => $author
        );

        if ($update = Attribute::findOrFail($id)->update($post)) {

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
            return redirect()->route('attributes');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('attributes.edit', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $attribute = Attribute::findOrFail($id);

        if ($delete = Attribute::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó respuesta "' . $attribute->type . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('attributes');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('attributes');
        }
    }


    public function status()
    {
        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $attribute = Attribute::findOrFail($id);

            $active = ($attribute->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Attribute::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "badge-success" : "badge-default";
                $text_status = ($active == 1) ? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

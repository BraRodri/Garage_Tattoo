<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Pages;
use Application\Helper;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    private $title = 'Páginas';
    private $module = 'pages';
    private $image_description = '800 x 600';
    public function index()
    {



        $pages = Pages::orderBy('id', 'desc')->get();

        return view('admvisch.pages.index')->with(['title' => $this->title, 'module' => $this->module, 'pages' => $pages, 'image_description' => $this->image_description]);
    }

    public function enter()
    {


        return view('admvisch.pages.enter')->with(['title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description]);
    }

    public function insert(Request $request)
    {

        $request->validate(
            [
                'title' => 'required'
            ]

        );

        $pages = Pages::where(['title' => Helper::postValue('title')])->get()->count();
        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        if ($pages > 0) {

            session()->flash('error', 'duplicate');
            return redirect()->route('pages.enter');
        } else {



            $post = array(
                'title' => Helper::postValue('title'),
                'introduction' => Helper::postValue('introduction'),
                'description' => Helper::postValue('description'),
                'meta_title' => Helper::postValue('meta_title'),
                'meta_description' => Helper::postValue('meta_description'),
                'meta_keyword' => Helper::postValue('meta_keyword'),
                'meta_author' => Helper::postValue('meta_author'),
                'meta_robots' => Helper::postValue('meta_robots'),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($insert = Pages::create($post)) {
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
                        'detail' => 'Ingresó nueva página "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('pages');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('pages.enter');
            }
        }
    }

    public function edit($id)
    {

        $page = Pages::findOrFail($id);

        return view('admvisch.pages.edit')->with(['title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description, 'page' => $page]);
    }

    public function update()
    {

        $id = Helper::postValue('id');

        $pages = Pages::where('title', Helper::postValue('title'))->where('id', '<>', $id)->get()->count();
        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        if ($pages > 0) {

            session()->flash('error', 'duplicate');
            return redirect()->route('pages.edit', $id);
        }

        $post = array(
            'title' => Helper::postValue('title'),
            'introduction' => Helper::postValue('introduction'),
            'description' => Helper::postValue('description'),
            'meta_title' => Helper::postValue('meta_title'),
            'meta_description' => Helper::postValue('meta_description'),
            'meta_keyword' => Helper::postValue('meta_keyword'),
            'meta_author' => Helper::postValue('meta_author'),
            'meta_robots' => Helper::postValue('meta_robots'),
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );



        if ($update = Pages::findOrFail($id)->update($post)) {
            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ACTUALIZACION',
                    'identifier' => $id,
                    'detail' => 'Actualizó página "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('pages');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('pages.edit',$id);
        }
    }


    public function delete($id)
    {

        $page = Pages::findOrFail($id);

        if ($delete = Pages::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó página "' . $page->title . '" con ID N°' . $id . '.'
                ]);
            }

          
            session()->flash('error', 'success');
           return redirect()->route('pages');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('pages');
        }
    }

    public function status()
    {

        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $page = Pages::findOrFail($id);

            $active = ($page->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Helper::sessionSystemValue('user_name')
            );

            if ($update = Pages::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "badge-success" : "badge-default";
                $text_status = ($active == 1) ? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

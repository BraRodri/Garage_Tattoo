<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Log;
use App\Models\Blog;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $title = 'Entradas';
    private $module = 'blog';
    private $table_name = 'blog';
    private $image_description = '1080 x 450';

    public function index()
    {
        //
        $blog = Blog::orderBy('created_at')->get();
        return view('admvisch.blog.index')->with(['title' => $this->title, 'module' => $this->module, 'blogs' => $blog]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enter()
    {
        //
        return view('admvisch.blog.enter')->with(['title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'description1' => 'required',
            'image' => 'required|image|max:2048',
            'active' => 'required'
        ]);

        $image = $request->file('image')->store('public/imagenes');
        $url = Storage::url($image);

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'slug' => Str::slug(Helper::postValue('title'), '-'),
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description1'),
            'date_public' => Helper::getDate($hour = false),
            'image_main' => $url,
            'active' => Helper::postValue('active', 0),
            'author' => $author
        );

        if ($insert = Blog::create($post)) {
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
                    'detail' => 'Ingresó nueva entrada de blog "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('admin.blog');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('blog.enter');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $blog = Blog::findOrFail($id);
        return view('admvisch.blog.edit')->with(['blog' => $blog, 'title' => $this->title, 'module' => $this->module, 'image_description' => $this->image_description]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $id = Helper::postValue('id');
        $blog = Blog::findOrFail($id);

        //imagen
        if ($request->hasFile('image')) {
            // busco la imagen anterior y la elimino

            $url_anterior = str_replace('storage', 'public', $blog->image_main);
            Storage::delete($url_anterior);

            //agrego la nueva imagen
            $image = $request->file('image')->store('public/imagenes');
            $url = Storage::url($image);
        }

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'slug' => Str::slug(Helper::postValue('title'), '-'),
            'title' => Helper::postValue('title'),
            'description' => Helper::postValue('description1'),
            'date_public' => Helper::getDate($hour = false),
            'active' => Helper::postValue('active'),
            'author' => $author
        );

        if (!empty($image)) {
            $post['image_main'] = $url;
        }

        if ($update = Blog::findOrFail($id)->update($post)) {
            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ACTUALIZACION',
                    'identifier' => $id,
                    'detail' => 'Actualizó entrada blog "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('admin.blog');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('blog.edit', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $blog = Blog::findOrFail($id);

        if ($delete = Blog::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó respuesta "' . $blog->id . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('admin.blog');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('admin.blog');
        }
    }

    public function status()
    {
        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $blog = Blog::findOrFail($id);

            $active = ($blog->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Blog::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "badge-success" : "badge-default";
                $text_status = ($active == 1) ? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function uploadImage_editor(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('files/blog_images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('files/blog_images/' . $fileName);
            $msg = 'Imagen subida correctamente!';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}

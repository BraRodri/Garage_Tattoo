<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Metadata;
use Application\Helper;
use Illuminate\Http\Request;

class MetadataController extends Controller
{
    private $title = 'Metadatos';
    private $parent_title = 'Configuración Global';
    private $module = 'metadata';


    public function index(){

      

        $metadata = Metadata::orderBy('id', 'desc')->get();

        return view('admvisch.metadata.index')->with(['title'=>$this->title, 'parent_title'=>$this->parent_title, 'module'=>$this->module, 'metadata'=>$metadata]);
    }

    public function enter(){

    }

    public function insert(){


        $post = array(
            'friendly_url' => Helper::postValue('friendly_url', 0),
            'title' => Helper::postValue('title'),
            'authors' => Helper::postValue('authors'),
            'subject' => Helper::postValue('subject'),
            'description' => Helper::postValue('description'),
            'keyword' => Helper::postValue('keyword'),
            'language' => Helper::postValue('language'),
            'indexing' => Helper::postValue('indexing'),
            'robots' => Helper::postValue('robots'),
            'googlebots' => Helper::postValue('googlebots'),
            'distribution' => Helper::postValue('distribution'),
            'googlecode' => Helper::postValue('googlecode'),
            'analyticcode' => Helper::postValue('analyticcode'),
            'pixelcode' => Helper::postValue('pixelcode'),
            'author' => Helper::sessionSystemValue('user_name')
        );

        if ($insert = Metadata::create($post))
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
                    'detail' => 'Ingresó nueva metadata "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/success');
        } else {
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/enter/failure');
        }
    }

    public function edit($id){

        $metadata = Metadata::findOrFail($id);

        return view('admvisch.metadata.edit')->with(['title'=>$this->title, 'parent_title'=>$this->parent_title, 'module'=>$this->module, 'metadata'=>$metadata]);

    }

    public function update(){


        $id = Helper::postValue('id');
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $post = array(
            'friendly_url' => Helper::postValue('friendly_url', 0),
            'title' => Helper::postValue('title'),
            'authors' => Helper::postValue('authors'),
            'subject' => Helper::postValue('subject'),
            'description' => Helper::postValue('description'),
            'keyword' => Helper::postValue('keyword'),
            'language' => Helper::postValue('language'),
            'indexing' => Helper::postValue('indexing'),
            'robots' => Helper::postValue('robots'),
            'googlebots' => Helper::postValue('googlebots'),
            'distribution' => Helper::postValue('distribution'),
            'googlecode' => Helper::postValue('googlecode'),
            'analyticcode' => Helper::postValue('analyticcode'),
            'pixelcode' => Helper::postValue('pixelcode'),
            'author' => $author
        );

        if ($update = Metadata::findOrFail($id)->update($post))
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
                    'detail' => 'Actualizó metadata "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('metadata.edit',$id);
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('metadata.edit',$id);
        }
    }

    public function delete($id){


        $metadata = Metadata::findOrFail($id);

        if($delete = Metadata::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó metadata "' . $metadata->title . '" con ID N°' . $id . '.'
                ]);
            }

            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/success');
        } else {
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/failure');
        }
    }
}

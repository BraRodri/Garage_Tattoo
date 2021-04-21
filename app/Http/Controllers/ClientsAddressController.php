<?php

namespace App\Http\Controllers;

use App\Models\ClientsAddress;
use App\Models\Locations;
use App\Models\Log;
use App\Models\Provinces;
use App\Models\Regions;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientsAddressController extends Controller
{
    
    private $title = 'Clientes Direcciones';
    private $parent_title = 'Clientes';
    private $module = 'clientsAddress';
    private $table_name= 'clients_address';


    public function index(){

        $clients_id = Helper::postValue('id');

        $table_documents_body = '';

        $addresses = ClientsAddress::where(['clients_id' => $clients_id])->orderBy('id', 'desc')->get();

        if(count($addresses) > 0) {
            foreach ($addresses AS $address) {

                $status = $actions = $address_name = '';

                $address_name  = $address->address;
                $address_name .= " N° " . $address->address_number;
                $address_name .= (!empty($address->office_number))? ", Depto / Oficina " . $address->office_number : '';
                $address_name .= ", " . $address->region->description;
                $address_name .= ", " . $address->province->description;
                $address_name .= ", " . $address->location->description;

                $class_status = ($address->active == 1)? "success" : "default";
                $text_status = ($address->active == 1)? "Activo" : "Inactivo";

                $status = '<a style="cursor: pointer;" class="change-status-address" id="' . $address->id . '"><span class="badge badge-' . $class_status . '">' . $text_status . '</span></a>';

               
                    $actions .= '<a type="button" class="btn btn-sm btn-gold show-form-edit-address" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" id="' .  $address->id . '"><i class="fa fa fa-pencil-square-o"></i></a> ';
              
               
                    $actions .= '<a type="button" class="btn btn-sm btn-danger delete-register-address" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" id="' .  $address->id . '"><i class="fa fa-trash-o"></i></a>';
              

                $table_documents_body .= '<tr>';
                $table_documents_body .= '<td>' . $address_name . '</td>';
                $table_documents_body .= '<td align="center">' . $status . '</td>';
                $table_documents_body .= '<td>' . Helper::dateFormatUser($address->updated_date) . '</td>';
                $table_documents_body .= '<td>' . $address->author . '</td>';
                $table_documents_body .= '<td>' . $actions . '</td>';
                $table_documents_body .= '</tr>';
            }
        } else {
            $table_documents_body .= '<tr>';
            $table_documents_body .= '<td colspan="5" align="center">No existen registros disponibles a visualizar</td>';
            $table_documents_body .= '</tr>';
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        echo json_encode(array(
            'data' => $table_documents_body
        ));
    }

    public function insert(){

        $clients_id = Helper::postValue('clients_id');

        $region = Regions::where(['code' => Helper::postValue('regions_id')])->first();
        $province = Provinces::where(['code' => Helper::postValue('provinces_id')])->first();
        $location = Locations::where(['code' => Helper::postValue('locations_id')])->first();

        $addresses = DB::select(
            'SELECT * FROM ' . $this->table_name . ' WHERE address = :address AND address_number = :address_number AND regions_id = :regions_id AND provinces_id = :provinces_id AND locations_id = :locations_id AND clients_id = :clients_id', [
                ':address' => Helper::postValue('address'),
                ':address_number' => Helper::postValue('address_number'),
                ':regions_id' => $region->id,
                ':provinces_id' => $province->id,
                ':locations_id' => $location->id,
                ':clients_id' => $clients_id
        ]);

        if(count($addresses) > 0){
            echo json_encode(array('error' => 0, 'type' => 'duplicate'));
        } else {

            $post = array(
                'clients_id' => $clients_id,
                'address_default' => 0,
                'address' => Helper::postValue('address'),
                'address_number' => Helper::postValue('address_number'),
                'office_number' => Helper::postValue('office_number'),
                'regions_id' => $region->id,
                'provinces_id' => $province->id,
                'locations_id' => $location->id,
                'active' => Helper::postValue('active', 0),
                'author' => Auth::user()->name
            );

            if ($insert = ClientsAddress::create($post))
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
                        'detail' => 'Ingresó nueva dirección "' . Helper::postValue('address') . '" con ID N°' . $id . '.'
                    ]);
                }

                echo json_encode(array('error' => 0, 'type' => 'success'));
            } else {
                echo json_encode(array('error' => 0, 'type' => 'failure'));
            }
        }
    }

    public function edit($id){

        $address = ClientsAddress::findOrFail($id);

        echo json_encode(array(
            'error' => 0,
            'id' => $address->id,
            'address' => $address->address,
            'address_number' => $address->address_number,
            'office_number' => $address->office_number,
            'regions_id' => $address->region->code,
            'provinces_id' => $address->province->code,
            'locations_id' => $address->location->code,
            'active' => $address->active,
        ));
    }

    public function update(){

        $id = Helper::postValue('id');
        $clients_id = Helper::postValue('clients_id');

        $region = Regions::where(['code' => Helper::postValue('regions_id')])->first();
        $province = Provinces::where(['code' => Helper::postValue('provinces_id')])->first();
        $location = Locations::where(['code' => Helper::postValue('locations_id')])->first();

        $addresses = DB::select(
            'SELECT * FROM ' . $this->table_name . ' WHERE address = :address AND address_number = :address_number AND regions_id = :regions_id AND provinces_id = :provinces_id AND locations_id = :locations_id AND clients_id = :clients_id AND id <> :id', [
                ':address' => Helper::postValue('address'),
                ':address_number' => Helper::postValue('address_number'),
                ':regions_id' => $region->id,
                ':provinces_id' => $province->id,
                ':locations_id' => $location->id,
                ':clients_id' => $clients_id,
                ':id' => $id
        ]);

        if(current($addresses) > 0){
            echo json_encode(array('error' => 0, 'type' => 'duplicate'));
        } else {

            $post = array(
                'address_default' => 0,
                'address' => Helper::postValue('address'),
                'address_number' => Helper::postValue('address_number'),
                'office_number' => Helper::postValue('office_number'),
                'regions_id' => $region->id,
                'provinces_id' => $province->id,
                'locations_id' => $location->id,
                'active' => Helper::postValue('active', 0),
                'author' => Auth::user()->name
            );

            if ($update = ClientsAddress::findOrFail($id)->update($post))
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
                        'detail' => 'Actualizó dirección "' . Helper::postValue('address') . '" con ID N°' . $id . '.'
                    ]);
                }

                echo json_encode(array('error' => 0, 'type' => 'success'));
            } else {
                echo json_encode(array('error' => 0, 'type' => 'failure'));
            }
        }
    }

    public function delete($id){

        $address = ClientsAddress::findOrFail($id);

        if($delete = ClientsAddress::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó dirección "' . $address->address . '" con ID N°' . $id . '.'
                ]);
            }

            echo json_encode(array('error' => 0, 'type' => 'success'));
        } else {
            echo json_encode(array('error' => 0, 'type' => 'failure'));
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $address = ClientsAddress::findOrFail($id);

            $active = ($address->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = ClientsAddress::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }
}

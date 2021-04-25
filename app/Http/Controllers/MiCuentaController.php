<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Clients;
use App\Models\Regions;
use Application\Helper;
use App\Models\Locations;
use App\Models\Provinces;
use Illuminate\Http\Request;
use App\Models\ClientsAddress;
use Illuminate\Support\Facades\Auth;

class MiCuentaController extends Controller
{
    //
    public function miCuenta()
    {
        $PaginaTitulo = "Mi Cuenta";
        if (Auth::guard('client')->user() != null) {
            return view('pages.miCuenta', compact('PaginaTitulo'));
        } else {
            return redirect()->route('login.client');
        }
    }

    public function misPedidos()
    {
        $PaginaTitulo = "Estado Pedidos";
        return view('pages.misPedidos', compact('PaginaTitulo'));
    }

    public function misDatos()
    {
        $PaginaTitulo = "Mis Datos de Cuenta";

        $id_cliente = Auth::guard('client')->user()->id;
        $datos = Clients::find($id_cliente);
        $address = ClientsAddress::where('active', 1)->get();
        $regions = Regions::orderBy('position')->get();
        $provinces = $locations = '';
        if (!empty($datos->regions_id)) {
            $provinces = Provinces::where(['parent_code' => $datos->region->code])->get();
        }
        if (!empty($datos->provinces_id)) {
            $locations = Locations::where(['parent_code' => $datos->province->code])->get();
        }

        if ($datos) {
            return view('pages.misDatos')
                ->with('PaginaTitulo', $PaginaTitulo)
                ->with('datos', $datos)
                ->with('regions', $regions)
                ->with('provinces', $provinces)
                ->with('locations', $locations)
                ->with('address', $address);
        }
    }

    public function misPuntos()
    {
        $PaginaTitulo = "Mis Puntos";
        return view('pages.misPuntos', compact('PaginaTitulo'));
    }

    public function updateMisDatos()
    {
        $id = Helper::postValue('id');

        $clients = Clients::where(['rut' => Helper::postValue('rut'), 'email' => Helper::postValue('email')])->where('id', '<>', $id)->get()->count();

        if ($clients > 0) {
            session()->flash('error', 'failure_datos');
            return redirect()->route('mis.datos');
        } else {

            $region = Regions::where(['code' => Helper::postValue('document_regions_id')])->first();
            $province = Provinces::where(['code' => Helper::postValue('document_provinces_id')])->first();
            $location = Locations::where(['code' => Helper::postValue('document_locations_id')])->first();

            $post = array(
                'rut' => Helper::postValue('rut'),
                'business_name' => Helper::postValue('business_name'),
                'address' => Helper::postValue('document_address'),
                'email' => Helper::postValue('email'),
                'phone' => Helper::postValue('phone'),
                'active' => 1,
            );

            if ($region) {
                $post['regions_id'] = $region->id;
            }
            if ($province) {
                $post['provinces_id'] = $province->id;
            }
            if ($location) {
                $post['locations_id'] = $location->id;
            }

            $new_password = Helper::postValue('password');
            if ($new_password) {
                $password = Helper::randomString(8, true, true, true, true);
                $post['password'] = bcrypt($new_password);
            }

            if ($update = Clients::findOrFail($id)->update($post)) {
                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'ACTUALIZACION',
                        'identifier' => $id,
                        'detail' => 'Actualizó cliente "' . Helper::postValue('rut') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success_datos');
                return redirect()->route('mis.datos');
            } else {
                session()->flash('error', 'failure_datos');
                return redirect()->route('mis.datos');
            }
        }
    }

    public function agregarNuevaDireccion(Request $request)
    {
        $request->validate([
            'alias' => 'required',
            'direccion' => 'required',
            'region_new' => 'required',
            'provincia_new' => 'required',
            'comuna_new' => 'required',
            'phone' => 'required'
        ]);

        $clients_id = Helper::postValue('idCliente');

        if ($clients_id) {

            $region = Regions::where(['code' => Helper::postValue('region_new')])->first();
            $province = Provinces::where(['code' => Helper::postValue('provincia_new')])->first();
            $location = Locations::where(['code' => Helper::postValue('comuna_new')])->first();

            $post = array(
                'clients_id' => Helper::postValue('idCliente'),
                'regions_id' => $region->id,
                'provinces_id' => $province->id,
                'locations_id' => $location->id,
                'address_default' => 0,
                'address' => Helper::postValue('direccion'),
                'alias' => Helper::postValue('alias'),
                'address_number' => Helper::postValue('phone'),
                'office_number' => '',
                'active' => 1,
                'author' => 'Cliente'
            );

            if ($insert = ClientsAddress::create($post)) {
                $id = $insert->id;

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => '',
                        'action' => 'NUEVO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nueva dirección "' . Helper::postValue('address') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success_address');
                return redirect()->route('mis.datos');
            } else {
                session()->flash('error', 'failure_datos');
                return redirect()->route('mis.datos');
            }
        }
    }

    public function eliminarDireccion($id)
    {
        $address = ClientsAddress::findOrFail($id);

        if ($delete = ClientsAddress::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => '',
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó dirección "' . $address->address . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success_address_delete');
            return redirect()->route('mis.datos');
        } else {
            session()->flash('error', 'failure_datos');
            return redirect()->route('mis.datos');
        }
    }

    public function updateAddressDatos()
    {
        $id = Helper::postValue('idAddress');
        $clients_id = Helper::postValue('idCliente');

        $region = Regions::where(['code' => Helper::postValue('region_new')])->first();
        $province = Provinces::where(['code' => Helper::postValue('provincia_new')])->first();
        $location = Locations::where(['code' => Helper::postValue('comuna_new')])->first();

        if ($clients_id) {

            $post = array(
                'clients_id' => $clients_id,
                'regions_id' => $region->id,
                'provinces_id' => $province->id,
                'locations_id' => $location->id,
                'address_default' => 0,
                'address' => Helper::postValue('direccion'),
                'alias' => Helper::postValue('alias'),
                'address_number' => Helper::postValue('phone'),
                'office_number' => '',
                'active' => 1,
                'author' => 'Cliente'
            );

            if ($update = ClientsAddress::findOrFail($id)->update($post)) {
                session()->flash('error', 'success_address_update');
                return redirect()->route('mis.datos');
            } else {
                session()->flash('error', 'failure_datos');
                return redirect()->route('mis.datos');
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CartList;
use App\Models\Cotizaciones;
use App\Models\CotizacionesDetalle;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\Products;
use App\Models\Regions;
use Application\Helper;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Transbank\Webpay\WebpayPlus\Transaction;



class CarroController extends Controller
{
    //


    public $buy_order;
    public $cotizacion;



    public function __construct()
    {
    }

    public function index()
    {
        $PaginaTitulo = "Mi Carro";
        $carro = Cart::getContent();
        $regions = Regions::orderBy('position')->get();

        return view('pages.carro')->with(['PaginaTitulo' => $PaginaTitulo, 'carro' => $carro, 'regions' => $regions]);
    }


    public function cotizar(Request $request)
    {

        $address = '';
        $region = '';
        $province = '';
        $location = '';
        $shipping_type = 1;
        $tipo_pago = $request->pago;

        if ($request->despacho == 'retiro-tienda') {

            $shipping_type = 2;
        } else if ($request->despacho == 'nuevo') {

            $address = $request->nueva_dir;
            $region = DB::select('select description from regions where code = ?', [$request->document_regions_id])[0]->description;
            $province = DB::select('select description from provinces where code = ?', [$request->document_provinces_id])[0]->description;
            $location = DB::select('select description from locations where code = ?', [$request->document_locations_id])[0]->description;
        } else {

            $address = Auth::guard('client')->user()->address;
            $region = Auth::guard('client')->user()->region->description;
            $province = Auth::guard('client')->user()->province->description;
            $location = Auth::guard('client')->user()->location->description;
        }

        $this->cotizacion = Cotizaciones::create([

            'clients_id' => Auth::guard('client')->user()->id,
            'address' => $address,
            'region_name' => $region,
            'province_name' => $province,
            'location_name' => $location,
            'shipping_type' => $shipping_type,
            'shipping_status' => 1,
            'payment_type' => $tipo_pago,
            'payment_status' => 1,
            'subtotal' => $request->subtotal,
            'total' => $request->total,
            'business_name' => Auth::guard('client')->user()->business_name,
            'rut' => Auth::guard('client')->user()->rut,
            'commercial_business' => Auth::guard('client')->user()->commercial_business,
            'email' => Auth::guard('client')->user()->email,
            'type_document' => 1


        ])->id;

        $cart_list = Cart::getContent();
        foreach ($cart_list as $detalle) {


            CotizacionesDetalle::create([
                'cotizaciones_id' => $this->cotizacion,
                'code' => $detalle->attributes['sku'],
                'description' => $detalle->name,
                'quantify' => $detalle->quantity || 1,
                'unit_price' => $detalle->price,
                'total_price' => $detalle->price,
                'weight' => 0,
                'height' => 0,
                'shipping_free' => 0,
                'talla' => $detalle->attributes['talla'],
                'color' => $detalle->attributes['color']

            ]);
        }
        return view('pagina.cotizacion-exitosa');
    }

    public function comprar(Request $request)
    {

        $details = $request->input('details');
        $carrito = $request->input('carro_id');
        $this->registrarPedido();
        $this->registrarPago();
        $this->destroyCarrito($details, $carrito);
        return view('pagina.comprafinal');
    }

    public function finalizarCompra(Request $request)
    {


        $pedido = self::registrarPedido($request);
        $pay = self::ejecutarPago($request);
        $total = $request->total;

        Cart::clear();


        return view('components.tienda.compra-finalizada')->with(['pay' => $pay, 'total' => $total, 'pedido' => $pedido]);
    }


    public function getTotal($despacho = 0, $subtotal)
    {

        return $despacho + $subtotal;
    }

    private function registrarPedido(Request $request)
    {

        $address = '';
        $region = '';
        $province = '';
        $location = '';
        $shipping_type = 1;
        $tipo_pago = $request->pago;

        if ($request->despacho == 'retiro-tienda') {

            $shipping_type = 2;
        } else if ($request->despacho == 'nuevo') {

            $address = $request->nueva_dir;
            $region = DB::select('select description from regions where code = ?', [$request->document_regions_id])[0]->description;
            $province = DB::select('select description from provinces where code = ?', [$request->document_provinces_id])[0]->description;
            $location = DB::select('select description from locations where code = ?', [$request->document_locations_id])[0]->description;
        } else {

            $address = Auth::guard('client')->user()->address;
            $region = Auth::guard('client')->user()->region->description;
            $province = Auth::guard('client')->user()->province->description;
            $location = Auth::guard('client')->user()->location->description;
        }

        $this->buy_order = Orders::create([

            'clients_id' => Auth::guard('client')->user()->id,
            'address' => $address,
            'region_name' => $region,
            'province_name' => $province,
            'location_name' => $location,
            'shipping_type' => $shipping_type,
            'shipping_status' => 1,
            'payment_type' => $tipo_pago,
            'payment_status' => 1,
            'subtotal' => $request->subtotal,
            'total' => $request->total,
            'business_name' => Auth::guard('client')->user()->business_name,
            'rut' => Auth::guard('client')->user()->rut,
            'commercial_business' => Auth::guard('client')->user()->commercial_business,
            'email' => Auth::guard('client')->user()->email,
            'type_document' => 1


        ])->id;

        $cart_list = Cart::getContent();
        foreach ($cart_list as $detalle) {


            OrdersDetails::create([
                'orders_id' => $this->buy_order,
                'code' => $detalle->attributes['sku'],
                'description' => $detalle->name,
                'quantify' => $detalle->quantity || 1,
                'unit_price' => $detalle->price,
                'total_price' => $detalle->price,
                'weight' => 0,
                'height' => 0,
                'shipping_free' => 0,
                'talla' => $detalle->attributes['talla'],
                'color' => $detalle->attributes['color']

            ]);
        }
        return $cart_list;
    }

    private function registrarPago()
    {

        $metodo_pago = Helper::postValue('pago');

        if ($metodo_pago == 'Webpay') {
        }
    }

    public  function destroyCarrito($details, $id)
    {

        foreach ($details as $detail) {
            $detail->delete();
        }

        CartList::findOrFail($id)->delete();
    }

    public function update(Request $request)
    {

        $contador = 0;
        foreach (Cart::getContent() as $key => $details) {
            Cart::update($details->id, array(
                'id' => $details->id,
                'quantity' => array(
                    'relative' => false,
                    'value' => $request->quantity[$contador]
                ),

            ));

            $contador++;
        }


        session()->flash('error', 'actualizado');

        return back();
    }

    private function ejecutarPago(Request $request)
    {
        //Colsultar codigo comercio
        $total = Orders::find($this->buy_order);
        $res = Transaction::create($this->buy_order, Auth::guard('client')->user()->id, $total->total, 'https://www.segval.cl/v2/public/mi-carrito/compra-exitosa');
        return $res;
    }

    public function compraExitosa()
    {
        return view('components.tienda.compra-exitosa');
    }
}

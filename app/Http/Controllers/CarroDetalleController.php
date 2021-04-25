<?php

namespace App\Http\Controllers;

use App\Models\CartList;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\Products;
use Application\Helper;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarroDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $product = Products::find($request->products_id);
        $productGallleries = $product->galleries;
        $price_product = $product->offer_price == 0 ? $product->normal_price : $product->offer_price;
        $quantity = (int)$request->quantity;

        Cart::add(array(
            'id' => $product->id,
            'name' => $product->title,
            'price' => $price_product,
            'quantity' => $request->quantity,
            'attributes' => array('color' => $request->color, 'talla' => $request->talla, 'sku' => $product->sku, 'medida' => $product->medida, 'galleries' => $productGallleries),
            'associatedModel' => $product
        ));

        session()->flash('error', 'success');
        return back();
    }

    private function registrarPedido()
    {

        Orders::create([

            'clients_id' => Helper::postValue('clients_id'),
            'address' => Helper::postValue('address'),
            'region_name' => Helper::postValue('region_name'),
            'province_name' => Helper::postValue('province_name'),
            'location_name' => Helper::postValue('location_name'),
            'shipping_type' => Helper::postValue('shipping_type'),
            'payment_type' => Helper::postValue('payment_type'),
            'shipping_status' => Helper::postValue('shipping_status'),
            'payment_status' => Helper::postValue('payment_status'),
            'subtotal' => Helper::postValue('subtotal'),
            'total' => Helper::postValue('total'),
            'width' => Helper::postValue('width'),
            'height' => Helper::postValue('height'),
            'length' => Helper::postValue('length'),
            'weight' => Helper::postValue('weight'),
            'talla' => Helper::postValue('talla'),
            'color' => Helper::postValue('color')

        ]);

        OrdersDetails::create();
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Cart::remove($id);
        session()->flash('error', 'eliminado');
        return back();
    }

    public function finalizarCompra(Request $request)
    {


        /**$pedido = self::registrarPedido($request);
        $pay = self::ejecutarPago($request);
        $total = $request->total;


        $this->destroyCarrito(CartList::get_session_cart_list()->details,Session::get('shopping_cart_id'));
        return view('components.tienda.compra-finalizada')->with(['pay' => $pay, 'total' => $total , 'pedido' => $pedido]);
         **/
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Models\Cotizaciones;
use App\Models\Orders;
use Application\Helper;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Help;

class IndexController extends Controller
{
    private $title = 'Home';
    private $module = 'index';

    public function index(){
        return view('admvisch.dashboard');
    }

    public function sidebar(){
        $sidebar_collapsed = Helper::postValue('sidebar_collapsed');

        if($sidebar_collapsed == true){
            session(['sidebar_collapsed' => true]);
        } else {
            session(['sidebar_collapsed' => false]);
        }

        echo json_encode(array('sidebar_collapsed' => $sidebar_collapsed));
    }

    public function notifications(){
       
    /*
        $number_orders = $content_contacts = $number_suscriptions = $number_cotizaciones = 0;
        // NOTIFICACIONES DE ORDENES DE COMPRAS
            $number_orders = Orders::where('payment_status', '<>', 4)->get()->count();     
        // NOTIFICACIONES DE COTIZACIONES
            $number_cotizaciones = Cotizaciones::get()->count();
            $content_contacts = Contacts::get()->count();

        echo json_encode(array(
            'error' => 0,
            'number_orders' => $number_orders,
            'number_cotizaciones' => $number_cotizaciones,
            'content_contacts' => $content_contacts,
            'number_suscriptions' => $number_suscriptions
        ));
        */
    }

    public static function postValue($varName, $default = null, $emptyDefault = false)
    {
        $value = $default;
        if (isset($_POST[$varName])) {
            $value = $_POST[$varName];
            if ($emptyDefault && empty($value)) {
                return $default;
            }
        }
        $value = self::customStripslashes($value);
        return $value;
    }

    public static function customStripslashes($value)
    {
        
            if (is_array($value)) {
                $value = array_map("customStripslashes", $value);
            } else {
                $value = stripslashes($value);
            }
        
        return $value;
    }

}

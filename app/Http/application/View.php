<?php
/**
 * Created by PhpStorm.
 * User: Ignacio Lincofil Briones <ilincofil@gmail.com>
 * Date: 21-03-2017
 * Time: 12:22
 */
namespace Application;

class View {

    private $_controller;
    public $route_view;
    public $route_js = array();
    public $route_include = array();
    public $module_name;

    public function __construct(Request $request){
        $this->_controller = $request->getController();
    }

    public function load($view){

        $this->route_view = ROOT . 'views' . DS . $this->_controller . DS . $view . '.phtml';
        $this->route_include = array(
            'path' => ROOT . 'views' . DS . $this->_controller . DS . 'include' . DS . $view . '.php',
            'url' => 'views' . '/' . $this->_controller . '/include/' . $view . '.php'
        );
        $this->route_js = array(
            'path' => ROOT . 'views' . DS . $this->_controller . DS . 'js' . DS . $view . '.js',
            'url' => BASE_URL . 'views' . '/' . $this->_controller . '/js/' . $view . '.js'
        );
        $this->module_name = $this->_controller;

        if(is_readable($this->route_view )){
            $session = Session::get('authenticated');
            if(isset($session) && $session === true) {
                include_once ROOT . 'public' . DS . 'themes' . DS . DEFAULT_LAYOUT . DS . 'index.php';
            } else {
                include_once ROOT . 'public' . DS . 'themes' . DS . DEFAULT_LAYOUT . DS . 'login.php';
            }
        } else {
            throw new Exception('Vista "' . $view . '" de Módulo "' . $this->_controller . '" no fue encontrada.');
        }
    }
}
?>
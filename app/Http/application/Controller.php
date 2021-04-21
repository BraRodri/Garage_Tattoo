<?php
/**
 * Created by PhpStorm.
 * User: Ignacio Lincofil Briones <ilincofil@gmail.com>
 * Date: 21-03-2017
 * Time: 12:21
 */
namespace Application;

abstract class Controller {

    protected $_view;
    protected $_request;
    protected $_acl;

    public function __construct(){
        $this->_view = new View(new Request);
        $this->_request = new Request();
        $this->_acl = new Acl();
    }

    abstract public function index();

    protected function redirect($route){
        if($route){
            header('location:' . BASE_URL . $route);
            exit();
        } else {
            header('location:' . BASE_URL);
            exit();
        }
    }

}
?>
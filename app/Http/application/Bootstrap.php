<?php
/**
 * Created by PhpStorm.
 * User: Ignacio Lincofil Briones <ilincofil@gmail.com>
 * Date: 21-03-2017
 * Time: 12:22
 */
namespace Application;

use Controllers;

class Bootstrap {

    public static function run(Request $request)
    {
        $controllerName = $request->getController();
        $routeController = ROOT . 'controllers' . DS . ucfirst($controllerName) . 'Controller.php';

        if(is_readable($routeController)){

            $controllerNameInstance = 'Controllers\\' . ucfirst($controllerName) . 'Controller';
            $controller = new $controllerNameInstance;

            if(is_callable(array($controller, $request->getMethod()))) {
                $methodName = $request->getMethod();
            } else {
                $methodName = DEFAULT_METHOD;
            }

            if(is_array($request->getArguments()) && count($request->getArguments()) > 0){
                call_user_func_array(array($controller, $methodName), $request->getArguments());
            } else {
                call_user_func(array($controller, $methodName));
            }
        } else {
            throw new Exception('Controlador "' . $request->getController() . '" no fue encontrado.');
        }
    }
}
?>
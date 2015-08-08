<?php

namespace App\Core\Http;


use Exception;

class Route {

    /**
     * @param Request $request
     * @throws Exception
     */
    public static function route(Request $request){

        $controller = ucfirst($request->getController()).'Controller';

        $method = $request->getMethod();


        $controllerFile = BASE_PATH.'controllers/'.$controller.'.php';


        $controllerName = 'App\\Controllers\\'.$controller;

        if(is_readable($controllerFile)){

            $controller = new $controllerName;

            if (!is_callable(array($controller,$method)))
                throw new Exception("No {$method} Method");

            call_user_func_array(array($controller,$method),$request->getArgs());

        } else
        throw new Exception('404 - '.$request->getController().' not found');
    }
}
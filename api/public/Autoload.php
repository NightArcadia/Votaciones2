<?php

use Slim\App;
use Slim\Container;

require './Config.php';
Config::requires();

class Autoload{

    public function __construct(){

        $modulo = self::modulo();

        DataBase::secureConnection();

        $app = new App(new Container(Config::CONFIGURATION));

        self::components($modulo, $app);

        try {
            $app->run();
        } catch (Throwable $e) {
            echo json_encode("Error en el servidor: " . $e->getMessage());
        }

    }

    public static function components($modulo, $app){
        $modulo = ucwords($modulo);
        $model = '../src/model/Model' . $modulo . '.php';
        $controller = '../src/controller/Controller' . $modulo . '.php';
        if( file_exists($model) && file_exists($controller) ){
            require $model;
            require $controller;
        }
    }

    public static function modulo(){
        $rutas = explode('/', $_SERVER['REQUEST_URI']);
        $ruta = implode('/', array_slice($rutas, array_search('public', $rutas) + 1));
        return explode('/', $ruta)[0];
    }

}
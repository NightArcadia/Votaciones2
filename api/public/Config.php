<?php

define("DOCUMENTROOT", $_SERVER['DOCUMENT_ROOT']);
define("CLOUD", "http://localhost/Votaciones/");
define("MAXIMO_HABITACIONES", 10);
//
//http://localhost/Votaciones/ 
class Config
{
    const SERVER        = "http://localhost/Votaciones/";
    const HOTELDILIGENCIAS          = self::SERVER . "Votaciones/";
    const PATH_HOTELDILIGENCIAS     = "Votaciones/";
    const RUTA_HOTELDILIGENCIAS   = DOCUMENTROOT . "/" . self::PATH_HOTELDILIGENCIAS;

    const CONFIGURATION = [
        'settings' => [
            'determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => true,
            'addContentLengthHeader' => false,
        ],
    ];

    public static function requires()
    {
        require '../vendor/autoload.php';
        require '../src/model/props/ModelProps.php';
        require '../src/model/db/ModelConexion.php';
        require '../src/model/db/ModelDataBase.php';
        require '../src/model/props/ModelResponse.php';
    }
}

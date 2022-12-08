<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/** @var \Slim\App $app */

$obj = new Usuarios();

$app->post('/usuarios/register', function(Request $request, Response $response) use ($obj) {

    $data = $request->getParsedBody();

    $obj->initAgrega( $data );

    return $response->getBody()->write( json_encode( $obj->register() ) );

});

$app->post('/usuarios/getUserByCURP', function(Request $request, Response $response) use ($obj) {

    $data = $request->getParsedBody();
    $obj->setCurp($data["Curp"]);

    return $response->getBody()->write( json_encode( $obj->getUserByCURP() ) );
});
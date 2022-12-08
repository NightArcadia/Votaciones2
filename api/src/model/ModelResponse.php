<?php

declare(strict_types=1);

class Respuesta{

    public static function response(bool $success, string $message):stdClass{

        $response = new stdClass();
        $response->success = $success;
        $response->message = $message;

        return $response;

    }

}
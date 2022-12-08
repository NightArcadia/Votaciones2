<?php

declare(strict_types=1);

class Props{

    public static function cadenaAleatoria( int $longitud ):string{
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $longitud);
    }

    public static function roundUp($amount, $decimals=2): float
    {
        $amount = $amount * pow(10, $decimals);
        $amount = ceil($amount);
        $amount = $amount/pow(10, $decimals);

        return floatval(number_format($amount, $decimals, '.', ''));
    }

}
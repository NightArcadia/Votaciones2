<?php

declare(strict_types=1);

interface InterfaceInit
{
        // Metodo con el que se auto-asignaran los valores recibidos en el web service
        // sin necesidad de asignar cada atributo manualmente
        public function init(array $args);
}

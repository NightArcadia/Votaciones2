<?php

class Conexion{

    private $host;
    private $user;
    private $pw;
    private $nombreBD;
    //localhost 
    public function __construct(){
        $this->host="database2.cvdx3yuticeu.us-east-2.rds.amazonaws.com";
        $this->user="Kuroro";
        $this->pw="12345678";
        $this->nombreBD="votacion";
    }

    public function conexion(){
        try {
            $dbConnection = new PDO("mysql:host=$this->host;dbname=$this->nombreBD;charset=utf8", $this->user, $this->pw);
            $dbConnection->exec("set names utf8");
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getNombreBD(){
        return $this->nombreBD;
    }

}
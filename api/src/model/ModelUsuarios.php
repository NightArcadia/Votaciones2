<?php

declare(strict_types=1);

class Usuarios{

    private $Id;
    private $Nombre;
    private $Password;
    private $Curp;
    private $Foto;


    public function initAgrega(array $data):void{ 
        $this->Nombre = $data["Nombre"]; 
        $this->Password = $data["Password"]; 
        $this->Curp = $data["Curp"];  
        $this->Foto = $data["Foto"]; 
    }
    
    public function setId(string $Id):void{
        $this->Id = $Id;
    }

    public function setNombre(string $Nombre):void{
        $this->Nombre = $Nombre;
    }

    public function setPassword(string $Password):void{
        $this->Password = $Password;
    }

    public function setCurp(string $Curp):void{
        $this->Curp = $Curp;
    }

    public function setFoto(string $Foto):void{
        $this->Foto = $Foto;
    }
    
    public function register():stdClass{
        $select = DataBase::executeSelect("SELECT * FROM personas WHERE Curp = ?", [$this->Curp]);
        if (!$select->success) {
            $query = "INSERT INTO personas (Nombre, Password, Curp, Foto) VALUES (?,?,?,?)";
            $params = [ $this->Nombre, password_hash($this->Password, PASSWORD_DEFAULT), $this->Curp, $this->Foto];
            return DataBase::executeInsert($query, $params);
        }
        return Respuesta::response(false, "El CURP ya se encuentra registrado");
    }

    public function getUserByCURP():stdClass{
        $select = DataBase::executeSelect("SELECT * FROM personas WHERE Curp = ?", [$this->Curp]);
        if ($select->success) {
            return $select;
        }
        return Respuesta::response(false, "El CURP introducido no se encuentra registrado.");
    }

  
}
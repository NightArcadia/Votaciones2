<?php

declare(strict_types=1);

class DataBase{

    private static $connection;

    public static function secureConnection() {
        if(is_null(self::$connection)) {

            /* ESTABLECEMOS LA CONEXIÓN */
            $objConnection = new Conexion();
            self::$connection = $objConnection->conexion();

        }
    }

    public static function closeConnection():stdClass {
        self::$connection = NULL;
        if(!self::$connection) {

            $response = Respuesta::response(true, "Conexion cerrada exitosamente");

        } else {

            $response = Respuesta::response(false, "Error cerrando conexion");

        }
        return $response;
    }

    public static function beginTransaction() {
        self::$connection->beginTransaction();
    }

    public static function commit() {
        self::$connection->commit();
    }

    public static function rollback() {
        self::$connection->commit();
    }
    
    public static function executeSelect(string $sql, array $params):stdClass{
        $response = Respuesta::response(false,"Error en la consulta");

        try{

            $result = self::select($sql,$params);

            if(count($result) > 0){
                if(isset($result[0]->codError)){
                    $response->codigo_error = $result[0]->codError;
                    $response->error =$result[0]->messageError;
                }else{
                    $response = Respuesta::response(true,"Consulta realizada");
                    $response->data = $result;
                }
            }else{
                $response->message = "Sin informacion";
                $response->error = $result;
            }

        }catch (Exception $e){
            $response->codigo_error = $e->getCode();
            $response->mensaje_error = $e->getMessage();
        }

        return $response;
    }

    public static function select(string $sql, array $params):array{
        $resultSelect = array();

        try{

            /* PREPARAMOS LA SENTENCIA SQL RECIBIDA */
            $prepare = self::$connection->prepare($sql);

            /* ASIGNAMOS LOS VALORES ENVIADOS */
            $cParams = count($params);
            for($x = 0; $x<$cParams; $x++)
                $prepare->bindParam($x+1, $params[$x]);

            /* EJECUTAMOS EL QUERY */
            $prepare->execute();

            /* GUARDAR LA INFORMACIÓN OBTENIDA */
            while($row = $prepare->fetch(PDO::FETCH_OBJ)){
                array_push($resultSelect, $row);
            }

        }catch(PDOException $e){

            $error = new stdClass();
            $error->codError = $e->getCode();
            $error->messageError = $e->getMessage();
            array_push($resultSelect, $error);

        }

        return $resultSelect;
    }

    public static function executeInsertUpdateDelete(string $query, array $params):stdClass{
        $response = Respuesta::response(false, "Error en la consulta");
        try {

            $result = self::insert_delete_update($query, $params);

            if( isset( $result->rowCount ) && $result->rowCount > 0 ){

                $response = Respuesta::response(true, "Consulta realizada");
                $response->id = $result->lastID;

            }else{
                $response->error = $result;
            }

        }catch(Exception $e){
            $response->error = $e->getMessage();
        }

        return $response;
    }

    public static function insert(string $sql, array $params):stdClass{
        return self::insert_delete_update($sql, $params);
    }

    public static function executeInsert(string $sql, array $params):stdClass{
        return self::executeInsertUpdateDelete($sql, $params);
    }

    public static function update(string $sql, array $params):stdClass{
        return self::insert_delete_update($sql, $params);
    }

    public static function executeUpdate(string $sql, array $params):stdClass{
        return self::executeInsertUpdateDelete($sql,$params);
    }

    public static function delete(string $sql, array $params):stdClass{
        return self::insert_delete_update($sql, $params);
    }

    public static function executeDelete(string $sql, array $params):stdClass{
        return self::executeInsertUpdateDelete($sql,$params);
    }

    private static function insert_delete_update(string $sql, array $params):stdClass{
        $resultInsert = new stdClass();

        try{
            
            /* ESTABLECEMOS CONEXIÓN CON LA BD */
            $connect = new Conexion();
            $con = $connect->conexion();
            /* PREPARAMOS EL QUERY */
            $prepare = $con->prepare($sql);

            /* ASIGNAMOS LOS VALORES ENVIADOS */
            $cParams = count($params);
            for($x = 0; $x<$cParams; $x++)
                $prepare->bindParam($x+1, $params[$x]);

            /* EJECUTAMOS EL QUERY */
            $prepare->execute();

            /* DEVOLVER CANTIDAD DE FILAS AFECTADAS */
            $resultInsert->rowCount = $prepare->rowCount();
            $resultInsert->lastID = $con->lastInsertId();

            /* CERRAMOS LA CONEXIÓN */
            $connect = null;
            $con = null;

        }catch(PDOException $e){

            $resultInsert->codError = $e->getCode();
            $resultInsert->messageError = $e->getMessage();

        }

        return $resultInsert;
    }

    public static function addSimpleColumn(string $table, array $params):array{

        /* CREAR LA ESTRUCTURA DEL QUERY POR CADA PARAMETRO ENVIADO */
        $query =    "ALTER TABLE $table ADD ".$params["name"]." "
            .$params["type"];

        if ($params["notnull"] === "TRUE") {
            $query .= " NOT NULL";
        }

        if (!empty($params["default"])) {
            $query .= " DEFAULT ".$params["default"];
        }

        return self::addColumn($query, $params);


    }

    public static function addForeignColumn(string $table, array $params):array{

        /* CREAR LA ESTRUCTURA DEL QUERY POR CADA PARAMETRO ENVIADO */
        $query =    "ALTER TABLE `$table` ADD `".$params["name"]."` int";

        if($params["notnull"] === "TRUE") {
            $query .= " NOT NULL";
        }

        $query .= ", ADD FOREIGN KEY (`".$params["name"]."`) "
            .  "REFERENCES ".$params["fkreference"]." "
            .  "on DELETE CASCADE on UPDATE CASCADE";

        return self::addColumn($query, $params);

    }

    private static function addColumn(string $sql, array $params):array {

        $resultAdd = array();

        try{

            /* PREPARAR LA SENTENCIA */
            $prepare = self::$connection->prepare($sql);

            /* EJECUTAMOS LA SENTENCIA */
            if($prepare->execute())
                $columnCount = count($params);

            /* RETORNAMOS LA RESPUESTA */
            $success = new stdClass();
            $success->columnCount = $columnCount;
            array_push($resultAdd, $success);

        } catch(Exception $e){

            $error = new stdClass();
            $error->codError = $e->getCode();
            $error->messageError = $e->getMessage();
            $error->query = $sql;
            array_push($resultAdd, $error);

        }

        return $resultAdd;

    }

}

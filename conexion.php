<?php

class Conexion{

    public static function ConexionBD(){

        $host='localhost';
        $dbname='Taller';
        $username='operadores';
        $password='telefono';
        $port=1433;
        
        try {
            $conn = new PDO ("sqlsrv:Server=$host,$port;Database=$dbname",$username, $password);
            //Echo "Se conecto a la base de datos $dbname"; 
        } catch (PDOException $exep ) {
            //echo ("No se conecto a la BD $dbname, error: $exep");
        }

        return $conn;
    }


}

?>
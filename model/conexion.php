<?php

 function abrir_conexion(){
                include_once 'config.php';
                try{
                $conexion = new PDO('mysql:host='.NOMBRE_SERVIDOR.'; dbname='.NOMBRE_BD,NOMBRE_USUARIO,PASSWORD);
                }catch(PDOException $ex){
                    throw new Exception("error en la conexion ".$ex->getMessage());
                }
                $conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conexion->exec("SET CHARACTER SET utf8");
                return $conexion;

        
    }

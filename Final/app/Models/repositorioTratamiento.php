<?php

namespace App\Models;

class RepositorioTratamiento{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function obtener_todos(){
        $todos=array();
        $conexion=abrir_conexion();
        if($conexion !==null){
            try{
                $sql= "SELECT * FROM tratamiento_farmacologico" ;
                $sentencia = $conexion ->prepare($sql);
                $sentencia->execute();
                $re=$sentencia ->fetchAll();
                if(count($re)){
                    foreach($re as $r){
                        $todos[]= new Tratamiento($r['id'],$r['nombre']);
                    }
                }
            }catch(PDOException $ex){
                throw new Exception ("error consulta repositorioTratamiento->obtener_todos ".$ex->getMessage());
            }
        }
        $conexion=null;
        return $todos;
    }
}

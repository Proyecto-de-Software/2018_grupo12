<?php

namespace App\Models;

/*include_once "conexion.php";
include_once "obraSocial.php";
*/
class RepositorioObraSocial
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function obtener_por_id($id)
    {
        $obraSocial = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM obra_social WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $obraSocial = new ObraSocial($r["id"], $r["nombre"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioObraSocial::obtener_por_id " . $ex->getMessage());

            }
        }
        $conexion = null;
        return $obraSocial;
    }
    public function obtener_todos(){
        $todos=array();
        $conexion=abrir_conexion();
        if($conexion !==null){
            try{
                $sql= "SELECT * FROM obra_social WHERE id>1";
                $sentencia = $conexion ->prepare($sql);
                $sentencia->execute();
                $re=$sentencia ->fetchAll();
                if(count($re)){
                    foreach($re as $r){
                        $todos[]= new ObraSocial($r['id'],$r['nombre']);
                    }
                }
            }catch(PDOException $ex){
                throw new Exception ("error consulta repositorioObraSocial->obtener_todos ".$ex->getMessage());
            }
        }
        $conexion=null;
        return $todos;
    }
}


<?php
include_once "conexion.php";
include_once "obraSocial.php";

class RepositorioObraSocial{

    public function obtener_por_id($id){
        $obraSocial=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
            $sql="SELECT * FROM obra_social WHERE id=:id";
            $sentencia=$conexion->prepare($sql);
            $sentencia->bindParam(":id",$id);
            $sentencia->execute();
            $r=$sentencia->fetch();
            if(!empty($r)){
                $obraSocial=new ObraSocial($r["id"],$r["nombre"]);
            } 
        }catch(PDOException $ex){
            throw new Exception("error consulta repositorioObraSocial::obtener_por_id ".$ex->getMessage());


        }
    }
    return $obraSocial;
}
}
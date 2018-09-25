
<?php
include_once "conexion.php";
include_once "regionSanitaria.php";

class RepositorioRegionSanitaria{

    public function obtener_por_id($id){
        $RegionSanitaria=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
            $sql="SELECT * FROM region_sanitaria WHERE id=:id";
            $sentencia=$conexion->prepare($sql);
            $sentencia->bindParam(":id",$id);
            $sentencia->execute();
            $r=$sentencia->fetch();
            if(!empty($r)){
                $RegionSanitaria=new RegionSanitaria($r["id"],$r["nombre"]);
            } 
        }catch(PDOException $ex){
            throw new Exception("error consulta repositorioRegionSanitaria::obtener_por_id ".$ex->getMessage());


        }
    }
    return $RegionSanitaria;
}
}
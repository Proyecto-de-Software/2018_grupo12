
<?php
include_once "conexion.php";
include_once "tipoDocumento.php";

class RepositorioTipoDocumento{

    public function obtener_por_id($id){
        $tipoDocumento=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
            $sql="SELECT * FROM tipo_documento WHERE id=:id";
            $sentencia=$conexion->prepare($sql);
            $sentencia->bindParam(":id",$id);
            $sentencia->execute();
            $r=$sentencia->fetch();
            if(!empty($r)){
                $tipoDocumento=new TipoDocumento($r["id"],$r["nombre"]);
            } 
        }catch(PDOException $ex){
            throw new Exception("error consulta repositorioTipoDocumento::obtener_por_id ".$ex->getMessage());


        }
    }
    return $tipoDocumento;
}
}
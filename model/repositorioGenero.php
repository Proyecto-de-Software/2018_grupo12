
<?php
include_once "conexion.php";
include_once "genero.php";

class RepositorioGenero{

    public function obtener_por_id($id){
        $genero=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
            $sql="SELECT * FROM genero WHERE id=:id";
            $sentencia=$conexion->prepare($sql);
            $sentencia->bindParam(":id",$id);
            $sentencia->execute();
            $r=$sentencia->fetch();
            if(!empty($r)){
                $genero=new Genero($r["id"],$r["nombre"]);
            } 
        }catch(PDOException $ex){
            throw new Exception("error consulta repositorioGenero::obtener_por_id ".$ex->getMessage());


        }
    }
    return $genero;
}
}

<?php
/*include_once "conexion.php";
include_once "localidad.php";
*/
class RepositorioLocalidad
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
        $Localidad = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM localidad WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $Localidad = new Localidad($r["id"], $r["nombre"], $r["partido_id"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioLocalidad::obtener_por_id " . $ex->getMessage());

            }
        }
        $conexion = null;
        return $Localidad;
    }
    public function obtener_todos(){
        $todos=array();
        $conexion=abrir_conexion();
        if($conexion !==null){
            try{
                $sql= "SELECT * FROM localidad WHERE id>1";
                $sentencia = $conexion ->prepare($sql);
                $sentencia->execute();
                $re=$sentencia ->fetchAll();
                if(count($re)){
                    foreach($re as $r){
                        $todos[]= new Localidad($r['id'],$r['nombre'],$r['partido_id']);
                    }
                }
            }catch(PDOException $ex){
                throw new Exception ("error consulta repositorioLocalidad->obtener_todos ".$ex->getMessage());
            }
        }
        $conexion=null;
        return $todos;
    }
}
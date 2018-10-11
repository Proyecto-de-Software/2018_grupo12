
<?php
/*include_once "conexion.php";
include_once "partido.php";
*/
class RepositorioPartido
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
        $Partido = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM partido WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $Partido = new Partido($r["id"], $r["nombre"], $r["region_sanitaria_id"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioPartido::obtener_por_id " . $ex->getMessage());

            }
        }
        $conexion = null;
        return $Partido;
    }
    public function obtener_todos(){
        $todos=array();
        $conexion=abrir_conexion();
        if($conexion !==null){
            try{
                $sql= "SELECT * FROM Partido WHERE id>1";
                $sentencia = $conexion ->prepare($sql);
                $sentencia->execute();
                $re=$sentencia ->fetchAll();
                if(count($re)){
                    foreach($re as $r){
                        $todos[]= new Partido($r['id'],$r['nombre'],$r['region_sanitaria_id']);
                    }
                }
            }catch(PDOException $ex){
                throw new Exception ("error consulta repositorioPartido->obtener_todos ".$ex->getMessage());
            }
        }
        $conexion=null;
        return $todos;
    }
}
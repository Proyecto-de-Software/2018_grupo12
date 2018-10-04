
<?php
include_once "conexion.php";
include_once "partido.php";

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
}
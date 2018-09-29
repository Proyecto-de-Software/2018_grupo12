
<?php
include_once "conexion.php";
include_once "Localidad.php";

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
}
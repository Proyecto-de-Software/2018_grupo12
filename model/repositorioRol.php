
<?php
include_once "conexion.php";
include_once "Rol.php";

class RepositorioRol
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
        $Rol = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM rol WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $Rol = new Rol($r["id"], $r["nombre"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioRol::obtener_por_id " . $ex->getMessage());

            }
        }
        return $Rol;
    }
    public function obtener_por_id_usuario($id)
    { /*si usuario no tiene roles el arreglo va a estar vacio
     */
        $roles = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT r.id,r.nombre
                    FROM rol r INNER JOIN usuario_tiene_rol ut ON(r.id=ut.rol_id)
                   WHERE ut.usuario_id=:id AND ut.eliminado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $roles[] = new Rol($r['id'], $r['nombre']);
                    }
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioRol->obtener_por_id_usuario " . $ex->getMessage());
            }
        }
        return $roles;
    }
    public function obtener_todos_los_roles()
    { /*array vacio si no hay roles en la BD */
        $roles = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM rol ";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $roles[] = new Rol($r['id'], $r['nombre']);
                    }
                }
            } catch (PDOException $ex) {
                throw new Exception("erro consulta repositorioRol-> obtener_todos_los_roles");
            }
        }
        return $roles;
    }
}
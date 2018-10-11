<?php



class RepositorioUsuarioTieneRol
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function relacion_existe($usuario_id, $rol_id, $estado)
    { /*para preguntar si existe no borrada $estado=0, preguntar si existe eliminado $estado=1 */
        $existe = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario_tiene_rol WHERE usuario_id=:usuario_id AND rol_id=:rol_id AND eliminado=:estado";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":usuario_id", $usuario_id);
                $sentencia->bindParam(":rol_id", $rol_id);
                $sentencia->bindParam(":estado", $estado);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioUsuarioTieneRol->relacion_existe " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $existe;
    }

    public function crearRelacion($usuario_id, $rol_id)
    {
        $relacion_creada = false;
        $conexion = abrir_conexion();
        $existe = $this->relacion_existe($usuario_id, $rol_id, 1);
        if ($conexion !== null) {
            if ($existe !== null) {
                if (!$existe) {
                    $sql = "INSERT INTO usuario_tiene_rol(usuario_id,rol_id,eliminado) VALUES (:usuario_id,:rol_id,0)";
                } else {
                    $sql = "UPDATE usuario_tiene_rol SET eliminado=0 WHERE usuario_id=:usuario_id AND rol_id=:rol_id";
                }
                try {
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(":usuario_id", $usuario_id);
                    $sentencia->bindParam(":rol_id", $rol_id);
                    $relacion_creada = $sentencia->execute();
                } catch (PDOException $ex) {
                    throw new Exception("error consulta crearRelacion " . $ex->getMessage());
                }
            }
        }
        $conexion = null;
        return $relacion_creada;
    }
    public function eliminarRelacion($usuario_id, $rol_id)
    {
        $relacion_eliminada = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario_tiene_rol SET eliminado=1 WHERE usuario_id=:usuario_id AND rol_id=:rol_id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":usuario_id", $usuario_id);
                $sentencia->bindParam(":rol_id", $rol_id);
                $relacion_eliminada = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("error consulta eliminar Relacion " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $relacion_eliminada;
    }
    public function usuarioRoles($usuario_id)
    {
        $roles = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario_tiene_rol WHERE usuario_id=:usuario_id AND eliminado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":usuario_id", $usuario_id);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $re) {
                        $roles[] = new UsuarioTieneRol($re["usuario_id"], $re["rol_id"], $re["eliminado"]);
                    }
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta usuarioRoles " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $roles;
    }
}

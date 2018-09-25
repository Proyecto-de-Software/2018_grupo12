<?php

include_once "conexion.php";
include_once "usuarioTieneRol.php";

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

    public function crearRelacion($usuario_id, $rol_id)
    {
        $relacion_creada = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "INSERT INTO usuario_tiene_rol(usuario_id,rol_id,eliminado) VALUES (:usuario_id,:rol_id,0)";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":usuario_id", $usuario_id);
                $sentencia->bindParam(":rol_id", $rol_id);
                $relacion_creada = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta crearRelacion " . $ex->getMessage());
            }
        }
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
        return $roles;
    }
}

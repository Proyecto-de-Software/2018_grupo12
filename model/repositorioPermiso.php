<?php
include_once "permiso.php";

class RepositorioPermiso
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
        $permiso = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM permiso WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $permiso = new Permiso($r["id"], $r["nombre"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioPermiso::obtener_por_id " . $ex->getMessage());

            }
        }
        $conexion = null;
        return $permiso;
    }

    public function permisos_id_usuario($usuario_id)
    {
        $permisos = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT distinct u.id,p.id,p.nombre,p.admin
                 FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                                INNER JOIN rol r ON (utr.rol_id=r.id)
                                INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                                INNER JOIN permiso p ON (rtp.permiso_id=p.id)
                 WHERE u.id=:usuario_id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":usuario_id", $usuario_id);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $permisos[] = new Permiso($r['id'], $r['nombre'],$r['admin']);
                    }
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioPermiso->permisos_id_usuario " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $permisos;
    }

}

<?php


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
                 WHERE u.id=:usuario_id AND utr.eliminado=0";
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
    public function modulos_id_usuario_admin($usuario_id,$admin){ /*retorna los modulos que pueden ser accedidos por el usuario */
        $modulos = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT distinct u.id,p.id,p.nombre,p.admin
                 FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                                INNER JOIN rol r ON (utr.rol_id=r.id)
                                INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                                INNER JOIN permiso p ON (rtp.permiso_id=p.id)
                 WHERE u.id=:usuario_id AND p.admin=:admin AND utr.eliminado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":usuario_id", $usuario_id);
                $sentencia->bindParam(":admin", $admin);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $modulo=explode("_",$r['nombre']);
                        $modulos[] = $modulo[0];
                    }
                  $modulos = array_unique($modulos);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioPermiso->modulos_id_usuario " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $modulos;

    }
    public function id_usuario_tiene_permiso($usuario_id,$permiso_completo)/*true si el usuario tiene el permiso(string) pasado por parametro */
    {
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT distinct u.id,p.id,p.nombre,p.admin
                 FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                                INNER JOIN rol r ON (utr.rol_id=r.id)
                                INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                                INNER JOIN permiso p ON (rtp.permiso_id=p.id)
                 WHERE u.id=:usuario_id AND p.nombre=:permiso_completo AND utr.eliminado = 0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":usuario_id", $usuario_id);
                $sentencia->bindParam(":permiso_completo",$permiso_completo);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    return true;
                    }

            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioPermiso->id_usuario_tiene_permiso" . $ex->getMessage());
            }
        }
        $conexion = null;
        return false;
    }
    public function permisos_id_usuario_modulo($usuario_id,$modulo){/*retorna los permisos que el usuario tiene en el modulo pasado por parametro */

            $permisos = array();
            $conexion = abrir_conexion();
            if ($conexion !== null) {
                try {
                    $modulo=$modulo."%";
                    $sql = "SELECT distinct u.id,p.id,p.nombre,p.admin
                     FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                                    INNER JOIN rol r ON (utr.rol_id=r.id)
                                    INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                                    INNER JOIN permiso p ON (rtp.permiso_id=p.id)
                     WHERE u.id=:usuario_id AND p.nombre LIKE :modulo AND utr.eliminado=0
                     ORDER BY p.id" ;
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(":usuario_id", $usuario_id);
                    $sentencia->bindParam(":modulo",$modulo);
                    $sentencia->execute();
                    $resultado = $sentencia->fetchAll();
                    if (count($resultado)) {
                        foreach ($resultado as $r) {
                            $acciones=explode("_",$r['nombre']);
                            $permisos[] = $acciones[1];
                        }
                    }
                } catch (PDOException $ex) {
                    throw new Exception("error consulta repositorioPermiso->permisos_id_usuario_modulo " . $ex->getMessage());
                }
            }
            $conexion = null;
            return $permisos;
        }



}

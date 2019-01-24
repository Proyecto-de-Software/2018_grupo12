<?php

namespace App\Models;

class RepositorioRolTienePermiso
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function rol_tiene_permiso($rol_id, $permiso_id)
    {
        $tiene = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM rol_tiene_permiso WHERE rol_id=:rol_id AND permiso_id=:permiso_id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":rol_id", $rol_id);
                $sentencia->bindParam(":permiso_id", $permiso_id);
                $sentencia->execute();
                $re = $sentencia->fetch();
                if (!empty($re)) {
                    $conexion = null;
                    return true;
                } else {
                    $conexion = null;
                    return false;
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioRolTienePermiso->rol_tiene_permiso " . $ex->getMessage());
            }
        }
    }
    public function agregar_permisos($rol_id, $permisos)
    {
        $result = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "INSERT INTO rol_tiene_permiso VALUES (:rol_id,:permiso_id)";
                $conexion->beginTransaction();
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":rol_id", $rol_id);
                $sentencia->bindParam(":permiso_id", $permiso_id);
                foreach ($permisos as $permiso_id) {
                    if (!($this->rol_tiene_permiso($rol_id, $permiso_id))) {
                        $sentencia->execute();
                    }
                }
                $result = $conexion->commit();
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioRolTienePermiso->egregar_permisos " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function info_rol($rol_id)
    {
        $resultado = array();
        $permisos = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT r.nombre as nom,r.id as rid, p.id as idp,p.nombre as nom2,p.admin as adm
                FROM rol r LEFT JOIN rol_tiene_permiso ro ON (r.id=ro.rol_id)
                                          LEFT JOIN permiso p ON(ro.permiso_id=p.id)
                WHERE r.id=:rol_id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":rol_id", $rol_id);
                $sentencia->execute();
                $re = $sentencia->fetchAll();
                if(count($re)){
                $r = $re[0];
                $resultado["nombre"] = $r["nom"];
                $resultado["id"] = $r['rid'];
                foreach ($re as $r) {
                    if(isset($r['nom2'])){
                    $permisos[] = array("nombre" => $r["nom2"], "id" => $r["idp"]);
                    }
                }
                $resultado["permisos"] = $permisos;
            }
            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioRolTienePermiso->info_rol " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $resultado;
    }

}

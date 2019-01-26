<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioPermiso
{
    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM permiso WHERE id=:id", [":id" => $id]);
            if (count($re)) {
                return new Permiso($re[0]->id, $re[0]->nombre, $re[0]->admin);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id permiso");
        }
    }

    public function permisos_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM permiso");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new Permiso($r->id, $r->nombre, $r->admin);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error permisos_todos permiso");
        }
        return $todos;
    }
    public function permisos_id_usuario($usuario_id)
    {
        $todos = array();
        try {
            $re = DB::select("SELECT distinct u.id,p.id,p.nombre,p.admin
        FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                       INNER JOIN rol r ON (utr.rol_id=r.id)
                       INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                       INNER JOIN permiso p ON (rtp.permiso_id=p.id)
        WHERE u.id=:usuario_id AND utr.eliminado=0", [":usuario_id" => $usuario_id]);
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new Permiso($r->id, $r->nombre, $r->admin);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error permisos_id_usuario permiso");
        }
        return $todos;
    }
    public function modulos_id_usuario_admin($usuario_id, $admin)
    {
        $modulos = array();
        try {
            $re = DB::select("SELECT distinct u.id,p.id,p.nombre,p.admin
        FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                       INNER JOIN rol r ON (utr.rol_id=r.id)
                       INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                       INNER JOIN permiso p ON (rtp.permiso_id=p.id)
        WHERE u.id=:usuario_id AND p.admin=:admin AND utr.eliminado=0", [":usuario_id" => $usuario_id, ":admin" => $admin]);
            if (count($re)) {
                foreach ($re as $r) {
                    $modulo = explode("_", $r->nombre);
                    $modulos[] = $modulo[0];
                }
                $modulos = array_unique($modulos);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error modulos_id_usuario_admin permisos");
        }
        return $modulos;
    }
    public function id_usuario_tiene_permiso($usuario_id, $permiso_completo)
    {
        try {
            $re = DB::Select("SELECT distinct u.id,p.id,p.nombre,p.admin
        FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                       INNER JOIN rol r ON (utr.rol_id=r.id)
                       INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                       INNER JOIN permiso p ON (rtp.permiso_id=p.id)
        WHERE u.id=:usuario_id AND p.nombre=:permiso_completo AND utr.eliminado = 0",
                [":permiso_completo" => $permiso_completo, ":usuario_id" => $usuario_id]);
            if (count($re)) {
                return true;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error id_usuario_tiene_permiso");
        }
        return false;
    }

    public function permisos_id_usuario_modulo($usuario_id, $modulo)
    {
        $permisos = array();
        try {
            $modulo = $modulo . "%";
            $re = DB::select("SELECT distinct u.id,p.id,p.nombre,p.admin
        FROM usuario u INNER JOIN usuario_tiene_rol utr ON (u.id=utr.usuario_id)
                       INNER JOIN rol r ON (utr.rol_id=r.id)
                       INNER JOIN rol_tiene_permiso rtp ON (r.id=rtp.rol_id)
                       INNER JOIN permiso p ON (rtp.permiso_id=p.id)
        WHERE u.id=:usuario_id AND p.nombre LIKE :modulo AND utr.eliminado=0
        ORDER BY p.id", [':usuario_id' => $usuario_id, ":modulo" => $modulo]);
            if (count($re)) {
                foreach ($re as $r) {
                    $acciones = explode("_", $r->nombre);
                    $permisos[] = $acciones[1];
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta permisos_id_usuario_modulo");
        }
        return $permisos;
    }
}

<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioUsuarioTieneRol
{
    public function relacion_existe($usuario_id, $rol_id, $estado)
    {
        try {
            $re = DB::select("SELECT * FROM usuario_tiene_rol WHERE usuario_id=:usuario_id AND rol_id=:rol_id AND eliminado=:estado",
                [":usuario_id" => $usuario_id, ":rol_id" => $rol_id, ":estado" => $estado]);
            if (count($re)) {
                return true;
            } else {
                return false;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta relacion_existe usuariorol");
        }
    }
    public function crearRelacion($usuario_id, $rol_id)
    {
        $ok = false;
        $existe = $this->relacion_existe($usuario_id, $rol_id, 1);
        if ($existe !== null) {
            try {
                if (!$existe) {
                    $ok = DB::insert("INSERT INTO usuario_tiene_rol(usuario_id,rol_id,eliminado) VALUES (:usuario_id,:rol_id,0)",
                        [":usuario_id" => $usuario_id, ":rol_id" => $rol_id]);
                } else {
                    $ok = DB::update("UPDATE usuario_tiene_rol SET eliminado=0 WHERE usuario_id=:usuario_id AND rol_id=:rol_id",
                        [":usuario_id" => $usuario_id, ":rol_id" => $rol_id]);
                }
            } catch (\Illuminate\Database\QueryException | PDOException $e) {
                throw new Exception("error crearRelacion usuariotienerol");
            }
            return (Boolean) $ok;
        }
    }
    public function eliminarRelacion($usuario_id, $rol_id)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE usuario_tiene_rol SET eliminado=1 WHERE usuario_id=:usuario_id AND rol_id=:rol_id",
                [":usuario_id" => $usuario_id, ":rol_id" => $rol_id]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error eliminarRelacion usuariotienerol");
        }return (Boolean) $ok;
    }
    public function usuarioRoles($usuario_id)
    {
        $roles = array();
        try {
            $resultado = DB::select("SELECT * FROM usuario_tiene_rol WHERE usuario_id=:usuario_id AND eliminado=0",
                [":usuario_id" => $usuario_id]);
            if (count($resultado)) {
                foreach ($resultado as $re) {
                    $roles[] = new UsuarioTieneRol($re->usuario_id, $re->rol_id, $re->eliminado);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error usuarioRoles usuariotienerol");
        }
        return $roles;
    }
    public function usuarios_activos_admin()
    {
        $cant = null;
        try {
            $re = DB::select("SELECT COUNT(*) as total
        FROM usuario_tiene_rol ut INNER JOIN usuario u ON(ut.usuario_id=u.id)
                                  INNER JOIN rol r ON(ut.rol_id=r.id)
        WHERE u.activo=1 AND r.nombre='administrador' AND ut.eliminado=0");
            if (count($re)) {
                $cant = $re[0]->total;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error usuarios_activos_admin usuariotienerol");
        }
        return strval($cant);
    }
    public function usuario_es_admin($id)
    {
        $es = false;
        try {
            $re = DB::select("SELECT * FROM
         usuario_tiene_rol ut INNER JOIN rol r ON(ut.rol_id=r.id)
         WHERE ut.eliminado=0 AND r.nombre='administrador' AND ut.usuario_id=:id",
                [':id' => $id]);
            if (count($re)) {
                $es = true;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error usuario es admin usuariotienerol");
        }
        return $es;
    }
}

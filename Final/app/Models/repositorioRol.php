<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioRol
{
    public function rol_existe($rol)
    {
        $existe = false;
        try {
            $re = DB::select("SELECT * FROM rol WHERE nombre = :nombre", [':nombre' => $rol]);
            if (count($re)) {
                $existe = true;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta rol_existe");
        }
        return $existe;
    }
    public function insertar_rol($nombre)
    {
        $ok = false;
        try {
            $ok = DB::insert("INSERT INTO rol(nombre) VALUES (:nombre)", [":nombre" => $nombre]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta insertar_rol");
        }
        return (Boolean) $ok;
    }

    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM rol WHERE id=:id", [':id' => $id]);
            if (count($re)) {
                return new Rol($re[0]->id, $re[0]->nombre);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_por_id");
        }
    }

    public function obtener_por_id_usuario($id)
    {
        $roles = array();
        try {
            $re = DB::select("SELECT r.id,r.nombre
        FROM rol r INNER JOIN usuario_tiene_rol ut ON(r.id=ut.rol_id)
       WHERE ut.usuario_id=:id AND ut.eliminado=0", ['id' => $id]);
            if (count($re)) {
                foreach ($re as $r) {
                    $roles[] = new Rol($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error repoRol consulta obtener_por_id_usuario");
        }
        return $roles;
    }

    public function obtener_todos_los_roles()
    {
        $todos = array();
        try {
            $response = DB::select("SELECT * FROM rol");
            if (count($response)) {
                foreach ($response as $r) {
                    $todos[] = new Rol($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta repositorioRol->obtener_todos_los_roles");
        }
        return $todos;
    }
    public function obtener_todos_los_roles_pagina($limite, $pag)
    {
        $result = array();
        $result['roles'] = array();
        $result['total_roles'] = 0;
        $roles = array();
        try {
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM rol LIMIT :primero,:limite", [":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $roles[] = new Rol($r->id, $r->nombre);
                }
                $result['roles'] = $roles;
                $result['total_roles'] = $this->obtener_cantidad();
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_todos_los_roles_pagina");
        }
        return $result;
    }
    public function obtener_roles_por_nombre($nombre, $limite, $pag)
    {
        $result = array();
        $result['roles'] = array();
        $result['total_roles'] = 0;
        $roles = array();
        try {
            $nombre = "%" . $nombre . "%";
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM rol WHERE nombre LIKE :nom LIMIT :primero,:limite", [":primero" => $primero, ":limite" => $limite, ":nom" => $nombre]);
            if (count($re)) {
                foreach ($re as $r) {
                    $roles[] = new Rol($r->id, $r->nombre);
                }
                $result['roles'] = $roles;
                $result['total_roles'] = $this->obtener_cantidad_nombre($nombre);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_roles_por_nombre");
        }
        return $result;
    }

    public function obtener_cantidad()
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM rol ");
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_cantidad rol");
        }
    }
    public function obtener_cantidad_nombre($nombre)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM rol WHERE nombre LIKE :nombre", [":nombre" => $nombre]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_cantidad_nombre rol");
        }
    }
    public function actualizar_rol($rol_id, $nombre)
    {
        $result = false;
        try {
            $result = DB::update("UPDATE rol SET nombre=:nombre WHERE id=:id", [":nombre" => $nombre, ":id" => $rol_id]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Expcetion("error actualizar_rol rol");
        }
        return (Boolean) $result;
    }

}

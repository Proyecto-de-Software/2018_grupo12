<?php
namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;

class RepositorioApi
{
    public function todos()
    {
        $result = array();
        try {
            $re = DB::select("SELECT i.id,i.nombre,i.director,i.direccion,i.telefono,r.id as region_sanitaria_id,r.nombre AS region_sanitaria_nombre,i.tipo_institucion_id,t.nombre AS tipo_institucion_nombre
            FROM institucion i INNER JOIN localidad l ON(i.localidad_id=l.id)
                               INNER JOIN partido p ON (l.partido_id=p.id)
                               INNER JOIN region_sanitaria r ON (p.region_sanitaria_id=r.id)
                               INNER JOIN tipo_institucion t ON(i.tipo_institucion_id=t.id)
                               ORDER BY i.id");
            foreach ($re as $r) {
                $r = json_decode(json_encode($r), true);
                $r = array_map(function ($n) {
                    if (is_numeric($n)) {
                        $n = strval($n);
                    }
                    return $n;
                }, $r);
                $result[] = $r;
            }
            return $result;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error todos");
        }
    }

    public function tiposDeInstituciones()
    {
        $result = array();
        try {
            $re = DB::select("SELECT * FROM tipo_institucion");
            foreach ($re as $r) {
                $r = json_decode(json_encode($r), true);
                $r = array_map(function ($n) {
                    if (is_numeric($n)) {
                        $n = strval($n);
                    }
                    return $n;
                }, $r);
                $result[] = $r;
            }
            return $result;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error tiposDeInstituciones");
        }
    }

    public function institucion_id($id)
    {
        try {
            $re = DB::select("SELECT i.id,i.nombre,i.director,i.direccion,i.telefono,r.id as region_sanitaria_id,r.nombre AS region_sanitaria_nombre,i.tipo_institucion_id,t.nombre AS tipo_institucion_nombre
            FROM institucion i INNER JOIN localidad l ON(i.localidad_id=l.id)
                                   INNER JOIN partido p ON (l.partido_id=p.id)
                                   INNER JOIN region_sanitaria r ON (p.region_sanitaria_id=r.id)
                               INNER JOIN tipo_institucion t ON(i.tipo_institucion_id=t.id)
                               WHERE i.id=:id", [":id" => $id]);
            if (count($re)) {
                $r = json_decode(json_encode($re[0]), true);
                $r = array_map(function ($n) {
                    if (is_numeric($n)) {
                        $n = strval($n);
                    }
                    return $n;
                }, $r);
                return $r;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error institucion_id");
        }
    }
    public function region_nombre($nombre)
    {
        $result = array();
        try {
            $re = DB::select("SELECT i.id,i.nombre,i.director,i.direccion,i.telefono,r.id as region_sanitaria_id,r.nombre AS region_sanitaria_nombre,i.tipo_institucion_id,t.nombre AS tipo_institucion_nombre, l.id as localidad_id, l.partido_id
            FROM institucion i INNER JOIN localidad l ON(i.localidad_id=l.id)
                                   INNER JOIN partido p ON (l.partido_id=p.id)
                                   INNER JOIN region_sanitaria r ON (p.region_sanitaria_id=r.id)
                               INNER JOIN tipo_institucion t ON(i.tipo_institucion_id=t.id)
                               WHERE r.nombre=:nombre
                               ORDER BY i.id", [":nombre" => $nombre]);
            foreach ($re as $r) {
                $r = json_decode(json_encode($r), true);
                $r = array_map(function ($n) {
                    if (is_numeric($n)) {
                        $n = strval($n);
                    }
                    return $n;
                }, $r);
                $result[] = $r;
            }
            return $result;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error region_nombre");
        }
    }

    public function localidadExiste($id)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM localidad WHERE id=:id", [":id" => $id]);
            if ($re[0]->total) {
                return true;
            } else {
                return false;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error localidadExiste" . $e->getMessage());
        }
    }
    public function tipoInstitucionExiste($id)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM tipo_institucion WHERE id=:id", [":id" => $id]);
            if ($re[0]->total) {
                return true;
            } else {
                return false;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error tipoInstitucionExiste" . $e->getMessage());
        }
    }
    public function insertarInstitucion($institucion)
    {
        try {
            $ok = DB::insert("INSERT INTO institucion (nombre,director,direccion
            ,telefono,localidad_id,tipo_institucion_id)
            VALUES (:nombre,:director,:direccion,:telefono,:localidad_id,:tipo_institucion_id)",
                [":nombre" => $institucion->getNombre(),
                    ":director" => $institucion->getDirector(),
                    ":direccion" => $institucion->getDireccion(),
                    ":telefono" => $institucion->getTelefono(),
                    ":localidad_id" => $institucion->getLocalidad_id(),
                    ":tipo_institucion_id" => $institucion->getTipo_institucion_id()]);
            return $ok;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error insertarInstitucion");
        }
    }
    public function actualizarInstitucion($institucion)
    {
        try {
            $ok = DB::update("UPDATE institucion SET nombre=:nombre,director=:director,direccion=:direccion,
            telefono=:telefono,localidad_id=:localidad_id,tipo_institucion_id=:tipo_institucion_id
            WHERE id=:id",
                [":id" => $institucion->getId(),
                    ":nombre" => $institucion->getNombre(),
                    ":director" => $institucion->getDirector(),
                    ":direccion" => $institucion->getDireccion(),
                    ":telefono" => $institucion->getTelefono(),
                    ":localidad_id" => $institucion->getLocalidad_id(),
                    ":tipo_institucion_id" => $institucion->getTipo_institucion_id()]);
            return $ok;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error actualizarInstitucion");
        }
    }

}

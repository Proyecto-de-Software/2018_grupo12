<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;


class RepositorioRolTienePermiso
{

    public function rol_tiene_permiso($rol_id, $permiso_id)
    {
        try {
            $re = DB::select("SELECT * FROM rol_tiene_permiso WHERE rol_id=:rol_id AND permiso_id=:permiso_id",
                [":rol_id" => $rol_id, ":permiso_id" => $permiso_id]);
            if (count($re)) {
                return true;
            } else {
                return false;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta rol_tiene_permiso");
        }
    }

    public function agregar_permisos2($rol_id,$permisos){
      try{
        DB::beginTransaction();
        foreach($permisos as $p){
          if(!($this->rol_tiene_permiso($rol_id,$p))){
            DB::insert("INSERT INTO rol_tiene_permiso VALUES (:rol_id,:permiso_id)",
            [":rol_id"=>$rol_id,":permiso_id"=>$p]);
          }          
        }
        $result=DB::commit();
        return true;
      }catch(\Illuminate\Database\QueryException | PDOException $e){
        DB::rollback();
        return false;
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
        try {
            $re = DB::select("SELECT r.nombre as nom,r.id as rid, p.id as idp,p.nombre as nom2,p.admin as adm
          FROM rol r LEFT JOIN rol_tiene_permiso ro ON (r.id=ro.rol_id)
                                    LEFT JOIN permiso p ON(ro.permiso_id=p.id)
          WHERE r.id=:rol_id", [":rol_id" => $rol_id]);
            $r = $re[0];
            $resultado["nombre"] = $r->nom;
            $resultado["id"] = $r->rid;
            foreach ($re as $r) {
                if (isset($r->nom2)) {
                    $permisos[] = array("nombre" => $r->nom2, "id" => $r->idp);
                }
            }
            $resultado["permisos"] = $permisos;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error info_rol");
        }return $resultado;
    }
}

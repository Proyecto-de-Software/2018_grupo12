<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioLocalidad
{
    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM localidad WHERE id=:id", [":id" => $id]);
            if (count($re)) {
                return new Localidad($re[0]->id, $re[0]->nombre, $re[0]->coordenadas, $re[0]->partido_id);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id localidad");
        }
    }

    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM localidad");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new Localidad($r->id, $r->nombre, $r->coordenadas, $r->partido_id);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos localidad");
        }
        return $todos;
    }
}

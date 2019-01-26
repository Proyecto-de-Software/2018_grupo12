<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

include_once "partido.php";

class RepositorioPartido
{
    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM partido WHERE id=:id", [":id" => $id]);
            if (count($re)) {
                return new Partido($re[0]->id, $re[0]->nombre, $re[0]->region_sanitaria_id);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id partido");
        }
    }

    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM partido");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new Partido($r->id, $r->nombre, $r->region_sanitaria_id);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos partido");
        }
        return $todos;
    }
}

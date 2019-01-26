<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioRegionSanitaria
{

    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM region_sanitaria WHERE id=:id", [":id" => $id]);
            if (count($re)) {
                return new RegionSanitaria($re[0]->id, $re[0]->nombre);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id region sanitaria");
        }
    }

    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM region_sanitaria");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new RegionSanitaria($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos region sanitaria");
        }
        return $todos;
    }
}

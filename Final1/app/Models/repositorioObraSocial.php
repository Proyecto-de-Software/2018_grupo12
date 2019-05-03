<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

include_once "obraSocial.php";

class RepositorioObraSocial
{
    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM obra_social WHERE id=:id", [":id" => $id]);
            if (count($re)) {
                return new ObraSocial($re[0]->id, $re[0]->nombre);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id obra social");
        }
    }

    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM obra_social");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new ObraSocial($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos obra social");
        }
        return $todos;
    }
}

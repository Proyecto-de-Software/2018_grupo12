<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioMotivo
{

    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM motivo_consulta");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new Motivo($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos motivo");
        }
        return $todos;
    }

}

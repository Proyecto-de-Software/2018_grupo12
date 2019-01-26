<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioTipoDocumento
{

    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM tipo_documento WHERE id=:id", [":id" => $id]);
            if (count($re)) {
                return new TipoDocumento($re[0]->id, $re[0]->nombre);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id tipo documento");
        }
    }

    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM tipo_documento");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new TipoDocumento($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos tipo documento");
        }
        return $todos;
    }

}

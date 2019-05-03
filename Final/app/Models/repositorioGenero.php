<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioGenero
{
    public function obtener_por_id($id)
    {
        try {
            $re = DB::select("SELECT * FROM genero WHERE id=:id", [":id" => $id]);
            if (count($re)) {
                return new Genero($re[0]->id, $re[0]->nombre);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id genero");
        }
    }

    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM genero");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new Genero($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos genero");
        }
        return $todos;
    }

}

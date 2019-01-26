<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioTratamiento
{
    public function obtener_todos()
    {
        $todos = array();
        try {
            $re = DB::select("SELECT * FROM tratamiento_farmacologico");
            if (count($re)) {
                foreach ($re as $r) {
                    $todos[] = new Tratamiento($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos tipo tratamiento");
        }
        return $todos;
    }
}

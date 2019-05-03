<?php
namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioAcompanamiento
{

    public function obtener_todos()
    {
        $todos = array();
        try {
            $response = DB::select("SELECT * FROM acompanamiento");
            if (count($response)) {
                foreach ($response as $r) {
                    $todos[] = new Acompanamiento($r->id, $r->nombre);
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta repositorioAcompanamiento->obtener_todos");
        }
        return $todos;
    }
}

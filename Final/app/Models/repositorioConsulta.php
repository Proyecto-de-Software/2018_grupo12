<?php
namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

include_once "conexion.php";

class RepositorioConsulta
{
    private function toNull($variable)
    {
        if ($variable == "" || $variable == 0) {
            $variable = null;
        }
        return $variable;
    }
    public function obtener_numero_consultas()
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM consulta WHERE borrado=0");
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_numero_consultas");
        }
    }
    public function obtener_numero_consultas_historia($nro)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM consulta c INNER JOIN paciente p ON(c.paciente_id=p.id)
        WHERE c.borrado=0 AND p.nro_historia_clinica LIKE :num", [":num" => $nro]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_numero_consultas_historia");
        }
    }
    public function obtener_numero_consultas_numero($nro)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM consulta c INNER JOIN paciente p ON(c.paciente_id=p.id)
         WHERE c.borrado=0 AND p.numero LIKE :num", [":num" => $nro]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_numero_consultas_numero");
        }
    }
    public function obtener_numero_consultas_doc($tipo_doc, $doc)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM consulta c INNER JOIN paciente p ON(c.paciente_id=p.id)
        WHERE c.borrado=0 AND p.tipo_doc_id=:tipo_doc AND p.numero LIKE :doc", [":doc" => $doc, "tipo_doc" => $tipo_doc]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_numero_consultas_doc");
        }
    }
    public function insertar_consulta($paciente_id, $fecha, $motivo_id, $derivacion_id, $articulacion_con_instituciones,
        $internacion, $diagnostico, $observaciones, $tratamiento_farmacologico_id, $acompanamiento_id) {
        $ok = false;
        try {
            $ok = DB::insert("INSERT INTO consulta (paciente_id,fecha,motivo_id,derivacion_id,articulacion_con_instituciones,
        internacion,diagnostico,observaciones,tratamiento_farmacologico_id,acompanamiento_id,borrado) VALUES
        (:paciente_id,:fecha,:motivo_id,:derivacion_id,:articulacion_con_institucion,
        :internacion,:diagnostico,:observaciones,:tratamiento_farmacologico_id,:acompanamiento_id,0)",
                ["paciente_id" => $paciente_id,
                    ":fecha" => $fecha,
                    ":motivo_id" => $motivo_id,
                    ":derivacion_id" => $this->toNull($derivacion_id),
                    ":articulacion_con_institucion" => $articulacion_con_instituciones,
                    ":internacion" => $internacion,
                    ":diagnostico" => $diagnostico,
                    ":observaciones" => $observaciones,
                    ":tratamiento_farmacologico_id" => $this->toNull($tratamiento_farmacologico_id),
                    ":acompanamiento_id" => $this->toNull($acompanamiento_id)]);
            return $ok;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error insertar_consulta");
        }
    }
    public function actualizar_consulta($id, $tratamiento_farmacologico_id, $articulacion_con_instituciones,
        $diagnostico, $observaciones) {
        $ok = false;
        try {
            $ok = DB::update("UPDATE consulta SET articulacion_con_instituciones=:articulacion_con_institucion,
         diagnostico=:diagnostico, observaciones=:observaciones, tratamiento_farmacologico_id=:tratamiento_farmacologico_id
        WHERE id=:id AND borrado=0",
                [":id" => $id,
                    ":articulacion_con_institucion" => $articulacion_con_instituciones,
                    ":diagnostico" => $diagnostico,
                    ":observaciones" => $observaciones,
                    ":tratamiento_farmacologico_id" => $this->toNull($tratamiento_farmacologico_id)]);
            return (Boolean) $ok;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error actualizar_consulta");
        }
    }
    public function obtener_por_id($id)
    {
        try {
            $re = DB::Select("SELECT * FROM consulta WHERE id=:id AND borrado=0", [":id" => $id]);
            if (count($re)) {
                $re = $re[0];
                $consulta = new Consulta($re->id, $re->paciente_id, $re->fecha, $re->motivo_id, $re->derivacion_id,
                    $re->articulacion_con_instituciones, $re->internacion, $re->diagnostico, $re->observaciones, $re->tratamiento_farmacologico_id,
                    $re->acompanamiento_id, $re->borrado);
                return $consulta;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id");
        }
    }
    public function obtener_info_completa($id)
    {
        try {
            $r = DB::select("SELECT Distinct c.id,c.paciente_id,p.nombre as nombre,p.apellido as apellido,c.fecha,m.nombre as motivo,i.nombre as institucion,c.articulacion_con_instituciones,c.internacion,
        c.diagnostico,c.observaciones,tf.nombre as tratamiento_farmacologico,tf.id as tratamiento_farmacologico_id,a.nombre as acompanamiento,td.nombre as tipo_documento,p.numero as documento,
        p.nro_historia_clinica as historia_clinica
        FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                        LEFT JOIN institucion i ON (c.derivacion_id=i.id)
                        LEFT JOIN tratamiento_farmacologico tf ON(c.tratamiento_farmacologico_id=tf.id)
                        LEFT JOIN acompanamiento a ON(c.acompanamiento_id=a.id)
                        INNER JOIN paciente p ON(c.paciente_id=p.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
        WHERE c.id=:id AND c.borrado=0", [":id" => $id]);
            if (count($r)) {
                $result = json_decode(json_encode($r[0]), true);
                $result = array_map(function ($n) {
                    if (is_numeric($n)) {
                        $n = strval($n);
                    }
                    return $n;
                }, $result);
                return $result;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_info_completa");
        }
    }

    public function eliminar_consulta($id)
    {
        try {
            $ok = DB::update("UPDATE consulta SET borrado=1 WHERE id=:id", [":id" => $id]);
            return (Boolean) $ok;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error eliminar_consulta");
        }
    }
    public function eliminar_consultas_id_paciente($id)
    {
        try {
            $ok = DB::update("UPDATE consulta SET borrado=1 WHERE paciente_id=:id", [":id" => $id]);
            return (Boolean) $ok;
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error eliminar_consultas_id_paciente");
        }
    }
    public function obtener_todos()
    {
        $result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        try {
            $re = DB::select("SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
        c.fecha,m.nombre as motivo,c.internacion, g.nombre as genero, l.nombre as localidad, p.nombre as nombre,p.apellido as apellido
        FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                        INNER JOIN paciente p ON(c.paciente_id=p.id)
                        LEFT JOIN genero g ON(p.genero_id=g.id)
                        LEFT JOIN localidad l ON(p.localidad_id=l.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
        WHERE c.borrado=0");
            if (count($re)) {
                foreach ($re as $r) {
                    $con = json_decode(json_encode($r), true);
                    $con = array_map(function ($n) {
                        if (is_numeric($n)) {
                            $n = strval($n);
                        }
                        return $n;
                    }, $con);
                    $consultas[] = $con;
                }
                $result['consultas'] = $consultas;
                $result['total_consultas'] = $this->obtener_numero_consultas();
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos consulta");
        }
        return $result;
    }
    public function obtener_todos_orden($campo, $limite, $pag)
    {
        $result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        try {
            $or = '';
            switch ($campo) {
                case 'genero':
                    $or = " ORDER BY genero";
                    break;
                case 'localidad':
                    $or = " ORDER BY localidad";
                    break;
                case 'motivo':
                    $or = " ORDER BY motivo";
                    break;
            }
            $sql = "SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
        c.fecha,m.nombre as motivo,c.internacion, g.nombre as genero, l.nombre as localidad, p.nombre as nombre,p.apellido as apellido
        FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                        INNER JOIN paciente p ON(c.paciente_id=p.id)
                        LEFT JOIN genero g ON(p.genero_id=g.id)
                        LEFT JOIN localidad l ON(p.localidad_id=l.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
        WHERE c.borrado=0";
            $li = " LIMIT :primero,:limite";
            $sql = $sql . $or;
            $sql = $sql . $li;
            $primero = $limite * ($pag - 1);
            $re = DB::select($sql, [":primer" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $con = json_decode(json_encode($r), true);
                    $con = array_map(function ($n) {
                        if (is_numeric($n)) {
                            $n = strval($n);
                        }
                        return $n;
                    }, $con);
                    $consultas[] = $con;
                }
                $result['consultas'] = $consultas;
                $result['total_consultas'] = $this->obtener_numero_consultas();
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos_orden consulta");
        }
        return $result;
    }
    public function obtener_todos_limite_pagina($limite, $pag)
    {
        $result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        try {
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
        c.fecha,m.nombre as motivo,c.internacion, p.nombre as nombre,p.apellido as apellido
        FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                        INNER JOIN paciente p ON(c.paciente_id=p.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
        WHERE c.borrado=0
        LIMIT :primero,:limite", [":limite" => $limite, ":primero" => $primero]);
            if (count($re)) {
                foreach ($re as $r) {
                    $con = json_decode(json_encode($r), true);
                    $con = array_map(function ($n) {
                        if (is_numeric($n)) {
                            $n = strval($n);
                        }
                        return $n;
                    }, $con);
                    $consultas[] = $con;
                }
                $result['consultas'] = $consultas;
                $result['total_consultas'] = $this->obtener_numero_consultas();
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos_limite_pagina consulta");
        }
        return $result;
    }
    public function obtener_historia_limite_pagina($nro, $limite, $pag)
    {
        $result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        try {
            $nro = "%" . $nro . "%";
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
        c.fecha,m.nombre as motivo,c.internacion, p.nombre as nombre,p.apellido as apellido
        FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                        INNER JOIN paciente p ON(c.paciente_id=p.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
        WHERE c.borrado=0 AND p.nro_historia_clinica LIKE :nro
        LIMIT :primero,:limite", [":nro" => $nro, ":limite" => $limite, ":primero" => $primero]);
            if (count($re)) {
                foreach ($re as $r) {
                    $con = json_decode(json_encode($r), true);
                    $con = array_map(function ($n) {
                        if (is_numeric($n)) {
                            $n = strval($n);
                        }
                        return $n;
                    }, $con);
                    $consultas[] = $con;
                }
                $result['consultas'] = $consultas;
                $result['total_consultas'] = $this->obtener_numero_consultas_historia($nro);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_historia_limite_pagina consulta");
        }
        return $result;
    }
    public function obtener_numero_limite_pagina($nro, $limite, $pag)
    {
        $result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        try {
            $nro = "%" . $nro . "%";
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
        c.fecha,m.nombre as motivo,c.internacion,p.nombre as nombre,p.apellido as apellido
        FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                        INNER JOIN paciente p ON(c.paciente_id=p.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
        WHERE c.borrado=0 AND p.numero LIKE :nro
        LIMIT :primero,:limite", [":nro" => $nro, ":limite" => $limite, ":primero" => $primero]);
            if (count($re)) {
                foreach ($re as $r) {
                    $con = json_decode(json_encode($r), true);
                    $con = array_map(function ($n) {
                        if (is_numeric($n)) {
                            $n = strval($n);
                        }
                        return $n;
                    }, $con);
                    $consultas[] = $con;
                }
                $result['consultas'] = $consultas;
                $result['total_consultas'] = $this->obtener_numero_consultas_numero($nro);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_numero_limite_pagina consulta");
        }
        return $result;
    }
    public function obtener_documento_limite_pagina($tipo_doc, $doc, $limite, $pag)
    {
        $result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        try {
            $doc = "%" . $doc . "%";
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
        c.fecha,m.nombre as motivo,c.internacion,p.nombre as nombre,p.apellido as apellido
        FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                        INNER JOIN paciente p ON(c.paciente_id=p.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
        WHERE c.borrado=0 AND p.tipo_doc_id=:tipo_doc AND p.numero LIKE :num
        LIMIT :primero,:limite", ["tipo_doc" => $tipo_doc, ":num" => $doc, ":limite" => $limite, ":primero" => $primero]);
            if (count($re)) {
                foreach ($re as $r) {
                    $con = json_decode(json_encode($r), true);
                    $con = array_map(function ($n) {
                        if (is_numeric($n)) {
                            $n = strval($n);
                        }
                        return $n;
                    }, $con);
                    $consultas[] = $con;
                }
                $result['consultas'] = $consultas;
                $result['total_consultas'] = $this->obtener_numero_consultas_doc($tipo_doc, $doc);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_documento_limite_pagina consulta");
        }
        return $result;
    }
    public function genero_porcentaje()
    {
        $result = array();
        try {
            $re = DB::select("SELECT distinct g.nombre as nombre ,COUNT(g.nombre) as cantidad
        FROM consulta c INNER JOIN paciente p ON (c.paciente_id=p.id)
                        INNER JOIN genero g ON (p.genero_id=g.id)
        WHERE c.borrado=0
        GROUP BY g.nombre");
            $total = $this->obtener_numero_consultas();
            $cant = 0;
            foreach ($re as $r) {
                $porcentaje = ($r->cantidad * 100) / $total;
                $result[] = array("nombre" => $r->nombre, "porcentaje" => $porcentaje);
                $cant = $cant + $r->cantidad;
            }
            if ($cant < $total) {
                $porcentaje = ((($total - $cant) * 100) / $total);
                $result[] = array("nombre" => "no se conoce", "porcentaje" => $porcentaje);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error genero_porcentaje consulta");
        }return $result;
    }
    public function motivo_porcentaje()
    {
        $result = array();
        try {
            $re = DB::select("SELECT distinct m.nombre as nombre ,COUNT(m.nombre) as cantidad
        FROM consulta c INNER JOIN motivo_consulta m ON (c.motivo_id=m.id)
        WHERE c.borrado=0
        GROUP BY m.nombre");
            $total = $this->obtener_numero_consultas();
            $cant = 0;
            foreach ($re as $r) {
                $porcentaje = ($r->cantidad * 100) / $total;
                $result[] = array("nombre" => $r->nombre, "porcentaje" => $porcentaje);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error motivo_porcentaje consulta");
        }return $result;
    }
    public function localidad_porcentaje()
    {
        $result = array();
        try {
            $re = DB::select("SELECT distinct l.nombre as nombre ,COUNT(l.nombre) as cantidad
        FROM consulta c INNER JOIN paciente p ON (c.paciente_id=p.id)
                        INNER JOIN localidad l ON (p.localidad_id=l.id)
        WHERE c.borrado=0
        GROUP BY l.nombre");
            $total = $this->obtener_numero_consultas();
            $cant = 0;
            foreach ($re as $r) {
                $porcentaje = ($r->cantidad * 100) / $total;
                $result[] = array("nombre" => $r->nombre, "porcentaje" => $porcentaje);
                $cant = $cant + $r->cantidad;
            }
            if ($cant < $total) {
                $porcentaje = ((($total - $cant) * 100) / $total);
                $result[] = array("nombre" => "no se conoce localidad", "porcentaje" => $porcentaje);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error localidad_porcentaje consulta");
        }return $result;
    }
}

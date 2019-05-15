<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioPaciente
{
    private function toNull($variable)
    {
        if ($variable == "" || $variable == 0) {
            $variable = null;
        }
        return $variable;
    }
    private function nullToWhiteSpace($variable)
    {
        if (!$variable) {
            $variable = " ";
        }
        return $variable;
    }
    private function cero($variable)
    {
        if ($variable == "") {
            $variable = 0;
        }
        return $variable;
    }
    private function fechaNull($variable)
    {
        if ($variable == "" || $variable == 0) {
            $variable = "0001-01-01";
        }
        return $variable;
    }
    public function obtener_numero_pacientes_nombre($nombre)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM paciente WHERE borrado=0 AND nombre LIKE :nombre"
                , [":nombre" => $nombre]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_pacientes_nombre");
        }
    }
    public function obtener_numero_pacientes_apellido($apellido)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM paciente WHERE borrado=0 AND apellido LIKE :apellido"
                , [":apellido" => $apellido]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_pacientes_apellido");
        }
    }
    public function obtener_numero_nombre_apellido($nombre, $apellido)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM paciente WHERE borrado=0 AND apellido LIKE :apellido AND NOMBRE LIKE :nombre"
                , [":apellido" => $apellido, ":nombre" => $nombre]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_nombre_apellido");
        }
    }
    public function obtener_numero_pacientes_hc($nro_historia)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM paciente WHERE borrado=0 AND nro_historia_clinica LIKE :nro"
                , [":nro" => $nro_historia]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_pacientes_hc");
        }
    }
    public function obtener_numero_pacientes()
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM paciente WHERE borrado=0");
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_pacientes");
        }
    }
    public function obtener_numero_pacientes_numero($num)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM paciente WHERE borrado=0 AND numero LIKE :num"
                , [":num" => $num]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_pacientes_numero");
        }
    }
    public function obtener_numero_datos_doc($tipo, $num)
    {
        try {
            $re = DB::select("SELECT COUNT(*) as total FROM paciente WHERE borrado=0 AND numero LIKE :num AND tipo_doc_id=:tipo_doc_id"
                , [":num" => $num, ":tipo_doc_id" => $tipo]);
            return strval($re[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_datos_doc");
        }
    }
    public function insertar_paciente($paciente)
    {
        $ok = false;
        try {
            $ok = DB::insert("INSERT INTO paciente(apellido,nombre,fecha_nac,lugar_nac,localidad_id,partido_id,region_sanitaria_id,
        domicilio,genero_id,tiene_documento,tipo_doc_id,numero,tel,nro_historia_clinica,nro_carpeta,obra_social_id,borrado)
        VALUES (:apellido,:nombre,:fecha_nac,:lugar_nac,:localidad_id,:partido_id,:region_sanitaria_id,:domicilio,
        :genero_id,:tiene_documento,:tipo_doc_id,:numero,:tel,:nro_historia_clinica,:nro_carpeta,:obra_social_id,0)",
                [":apellido" => $paciente->getApellido(),
                    ":nombre" => $paciente->getNombre(),
                    ":fecha_nac" => $paciente->getFecha_nac(),
                    ":lugar_nac" => $this->nullToWhiteSpace($paciente->getLugar_nac()),
                    ":localidad_id" => $paciente->getLocalidad_id(),
                    ":partido_id" => $paciente->getPartido_id(),
                    ":region_sanitaria_id" => $paciente->getRegion_sanitaria_id(),
                    ":domicilio" => $this->nullToWhiteSpace($paciente->getDomicilio()),
                    ":genero_id" => $paciente->getGenero_id(),
                    ":tiene_documento" => $paciente->getTiene_documento(),
                    ":tipo_doc_id" => $paciente->getTipo_doc_id(),
                    ":numero" => $paciente->getNumero(),
                    ":tel" => $this->nullToWhiteSpace($paciente->getTel()),
                    ":nro_historia_clinica" => $paciente->getNro_historia_clinica(),
                    ":nro_carpeta" => $paciente->getNro_carpeta(),
                    ":obra_social_id" => $paciente->getObra_social_id()]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error insertar_paciente");
        }
        return $ok;
    }
    public function insertar_nn($nro_historia_clinica)
    {
        $ok = false;
        try {
            $ok = DB::insert("INSERT INTO paciente(apellido,nombre,fecha_nac,lugar_nac,localidad_id,partido_id,region_sanitaria_id,
        domicilio,genero_id,tiene_documento,tipo_doc_id,numero,tel,nro_historia_clinica,nro_carpeta,obra_social_id,borrado)
        VALUES ('NN','NN','0001-01-01',' ',null,null,null,' ',null,0,null,0,' ',:nro_historia_clinica,0,null,0)",
                [":nro_historia_clinica" => $nro_historia_clinica]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error insertar_nn" . $e->getMessage());
        }
        return $ok;
    }
    public function eliminar_paciente($id)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE consulta SET borrado=1 WHERE paciente_id=:id", [":id" => $id]);
            $ok = DB::update("UPDATE paciente SET borrado=1 WHERE id=:id", [":id" => $id]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error eliminar_paciente");
        }
        return (Boolean) $ok;
    }
    public function actualizar_informacion($id, $apellido, $nombre, $fecha_nac, $lugar_nac, $localidad_id, $partido_id,
        $region_sanitaria_id, $domicilio, $genero_id, $tiene_documento, $tipo_doc_id, $numero, $tel,
        $nro_historia_clinica, $nro_carpeta, $obra_social_id) {
        $ok = false;
        try {
            $ok = DB::update("UPDATE paciente SET apellido=:apellido ,nombre=:nombre, fecha_nac=:fecha_nac,lugar_nac=:lugar_nac,
        localidad_id=:localidad_id, partido_id=:partido_id,region_sanitaria_id=:region_sanitaria_id, domicilio=:domicilio,
         genero_id=:genero_id, tiene_documento=:tiene_documento, tipo_doc_id=:tipo_doc_id,
          numero=:numero, tel=:tel, nro_historia_clinica=:nro_historia_clinica,nro_carpeta=:nro_carpeta,
          obra_social_id=:obra_social_id
          WHERE id=:id ",
                [":id" => $id,
                    ":apellido" => $apellido,
                    ":nombre" => $nombre,
                    ":fecha_nac" => $this->fechaNull($fecha_nac),
                    ":lugar_nac" => $lugar_nac,
                    ":localidad_id" => $this->toNull($localidad_id),
                    ":partido_id" => $this->toNull($partido_id),
                    ":region_sanitaria_id" => $this->toNull($region_sanitaria_id),
                    ":domicilio" => $domicilio,
                    ":genero_id" => $this->toNull($genero_id),
                    ":tiene_documento" => $this->cero($tiene_documento),
                    ":tipo_doc_id" => $this->toNull($tipo_doc_id),
                    ":numero" => $this->cero($numero),
                    ":tel" => $this->nullToWhiteSpace($tel),
                    ":nro_historia_clinica" => $this->cero($nro_historia_clinica),
                    ":nro_carpeta" => $this->cero($nro_carpeta),
                    ":obra_social_id" => $this->toNull($obra_social_id)]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error actualizar_informacion" . $e->getMessage());
        }
        return (Boolean) $ok;
    }

    public function existe_doc($tipo_doc, $num)
    {
        $paciente = array();
        try {
            $re = DB::select("SELECT * FROM paciente WHERE  tipo_doc_id=:tipo_doc_id AND numero =:num",
                [":num" => $num, ":tipo_doc_id" => $tipo_doc]);
            if (count($re)) {
              $r=$re[0];
                $paciente=new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error existe_doc");
        }
        return $paciente;
    }

    public function existe_historia_clinica($num)
    {
        $paciente = array();
        try {
            $re = DB::select("SELECT * FROM paciente WHERE nro_historia_clinica =:nro AND borrado = 0",
                [":nro" => $num]);
            if (count($re)) {
              $r=$re[0];
                $paciente=new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error existe_historia_clinica");
        }
        return $paciente;
    }
    public function obtener_por_nro_historia_clinica($nro, $limite, $pag)
    {
        $result = array();
        $result['pacientes'] = array();
        $result['total_pacientes'] = 0;
        $paciente = array();
        try {
            $primero = $limite * ($pag - 1);
            $nro = "%" . $nro . "%";
            $re = DB::select("SELECT * FROM paciente WHERE nro_historia_clinica LIKE :nro AND borrado=0 LIMIT :primero,:limite",
                [":nro" => $nro, ":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $paciente[] = new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                        $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                        $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
                }
                $result['pacientes'] = $paciente;
                $result['total_pacientes'] = $this->obtener_numero_pacientes_hc($nro);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_nro_historia_clinica");
        }
        return $result;
    }
    public function obtener_por_nombre($nombre, $limite, $pag)
    {
        $result = array();
        $result['pacientes'] = array();
        $result['total_pacientes'] = 0;
        $paciente = array();
        try {
            $primero = $limite * ($pag - 1);
            $nombre = "%" . $nombre . "%";
            $re = DB::select("SELECT * FROM paciente WHERE nombre LIKE :nombre  AND borrado=0 LIMIT :primero,:limite",
                [":nombre" => $nombre, ":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $paciente[] = new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                        $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                        $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
                }
                $result['pacientes'] = $paciente;
                $result['total_pacientes'] = $this->obtener_numero_pacientes_nombre($nombre);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_nombre");
        }
        return $result;
    }
    public function obtener_por_apellido($apellido, $limite, $pag)
    {
        $result = array();
        $result['pacientes'] = array();
        $result['total_pacientes'] = 0;
        $paciente = array();
        try {
            $primero = $limite * ($pag - 1);
            $apellido = "%" . $apellido . "%";
            $re = DB::select("SELECT * FROM paciente WHERE apellido LIKE :apellido  AND borrado=0 LIMIT :primero,:limite",
                [":apellido" => $apellido, ":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $paciente[] = new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                        $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                        $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
                }
                $result['pacientes'] = $paciente;
                $result['total_pacientes'] = $this->obtener_numero_pacientes_apellido($apellido);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_pro_apellido");
        }
        return $result;
    }
    public function obtener_por_nombre_y_apellido($nombre, $apellido, $limite, $pag)
    {
        $result = array();
        $result['pacientes'] = array();
        $result['total_pacientes'] = 0;
        $paciente = array();
        try {
            $primero = $limite * ($pag - 1);
            $nombre = "%" . $nombre . "%";
            $apellido = "%" . $apellido . "%";
            $re = DB::select("SELECT * FROM paciente WHERE nombre LIKE :nombre AND apellido LIKE :apellido AND borrado=0 LIMIT :primero,:limite",
                [":nombre" => $nombre, ":apellido" => $apellido, ":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $paciente[] = new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                        $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                        $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
                }
                $result['pacientes'] = $paciente;
                $result['total_pacientes'] = $this->obtener_numero_nombre_apellido($nombre, $apellido);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_nombre_y_apellido");
        }
        return $result;
    }
    public function obtener_por_num_doc($num, $limite, $pag)
    {
        $result = array();
        $result['pacientes'] = array();
        $result['total_pacientes'] = 0;
        $paciente = array();
        try {
            $primero = $limite * ($pag - 1);
            $num = "%" . $num . "%";
            $re = DB::select("SELECT * FROM paciente WHERE numero LIKE :num AND borrado=0 LIMIT :primero,:limite",
                [":num" => $num, ":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $paciente[] = new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                        $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                        $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
                }
                $result['pacientes'] = $paciente;
                $result['total_pacientes'] = $this->obtener_numero_pacientes_numero($num);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtenernum_doc");
        }
        return $result;
    }
    public function obtener_por_datos_doc($tipo_doc, $num, $limite, $pag)
    {
        $result = array();
        $result['pacientes'] = array();
        $result['total_pacientes'] = 0;
        $paciente = array();
        try {
            $primero = $limite * ($pag - 1);
            $num = "%" . $num . "%";
            $re = DB::select("SELECT * FROM paciente WHERE  tipo_doc_id=:tipo_doc_id AND numero LIKE :num AND borrado=0 LIMIT :primero,:limite",
                [":tipo_doc_id" => $tipo_doc, ":num" => $num, ":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $paciente[] = new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                        $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                        $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
                }
                $result['pacientes'] = $paciente;
                $result['total_pacientes'] = $this->obtener_numero_datos_doc($tipo_doc, $num);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_datos_doc");
        }
        return $result;
    }
    public function obtener_por_id($id)
    {
        try {
            $r = DB::select("SELECT * FROM paciente WHERE id=:nro AND borrado=0", ["nro" => $id]);
            if (count($r)) {
                $r = $r[0];
                return new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                    $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                    $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_por_id");
        }
    }

    public function obtener_por_id_info_completa($id)
    {
        try {
            $r = DB::select("SELECT Distinct p.id,p.apellido,p.nombre,p.fecha_nac,p.lugar_nac, l.nombre as localidad,l.id as localidad_id,pa.nombre as partido,pa.id as partido_id, r.nombre as region_sanitaria,r.id as region_sanitaria_id,p.domicilio,
        g.nombre as genero, g.id as genero_id,p.tiene_documento,td.nombre as tipo_doc, td.id as tipo_doc_id ,p.numero,p.tel,p.nro_historia_clinica,p.nro_carpeta,o.nombre as obra_social,o.id as obra_social_id,p.borrado
        FROM paciente p LEFT JOIN localidad l ON(p.localidad_id=l.id)
                        LEFT JOIN partido pa ON(p.partido_id=pa.id)
                        LEFT JOIN region_sanitaria r ON(p.region_sanitaria_id=r.id)
                        LEFT JOIN genero g ON(p.genero_id=g.id)
                        LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                        LEFT JOIN obra_social o ON(p.obra_social_id=o.id)
        WHERE p.id=:id AND borrado=0", [":id" => $id]);
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
            throw new Exception("error obtener_por_id_info_completa");
        }
    }
    public function obtener_todos_limite_pagina($limite, $pag)
    {
        $result = array();
        $result['pacientes'] = array();
        $result['total_pacientes'] = 0;
        $paciente = array();
        try {
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM paciente WHERE borrado=0  LIMIT :primero,:limite",
                [":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $paciente[] = new Paciente($r->id, $r->apellido, $r->nombre, $r->fecha_nac, $r->lugar_nac, $r->localidad_id, $r->partido_id,
                        $r->region_sanitaria_id, $r->domicilio, $r->genero_id, $r->tiene_documento, $r->tipo_doc_id, $r->numero, $r->tel,
                        $r->nro_historia_clinica, $r->nro_carpeta, $r->obra_social_id, $r->borrado);
                }
                $result['pacientes'] = $paciente;
                $result['total_pacientes'] = $this->obtener_numero_pacientes();
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error obtener_todos_limite_pagina");
        }
        return $result;
    }
    public function coordenadas_derivaciones($id)
    {
        $result = array();
        try {
            $re = DB::select("SELECT distinct l.coordenadas as coordenadas
        FROM paciente p INNER JOIN consulta c ON (p.id=c.paciente_id)
                        INNER JOIN institucion i ON (c.derivacion_id=i.id)
                        INNER JOIN localidad l ON (i.localidad_id=l.id)
         WHERE p.id=:id AND c.borrado=0", ["id" => $id]);
            foreach ($re as $r) {
                $result[] = $r->coordenadas;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error corrdenadas_derivaciones");
        }
        return $result;
    }
    public function paciente_tiene_consultas($id)
    {
        try {
            $re = DB::select("SELECT COUNT(c.id) as result
        FROM consulta c INNER JOIN paciente p ON (c.paciente_id=p.id)
        WHERE p.id=:id AND c.borrado=0", [":id" => $id]);
            if ($re[0]->result > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error paciente_tiene_consultas");
        }
    }

}

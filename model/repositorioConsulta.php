<?php

class RepositorioConsulta
{

    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    private function toNull($variable)
    {
        if ($variable == "" || $variable == 0) {
            $variable = null;
        }
        return $variable;
    }
    public function obtener_numero_consultas()
    {
        $total_consultas = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM consulta WHERE borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_consultas = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_consultas");
            }

        }
        $conexion = null;
        return $total_consultas;
    }
    public function obtener_numero_consultas_historia($nro)
    {
        $total_consultas = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM consulta c INNER JOIN paciente p ON(c.paciente_id=p.id) 
                WHERE c.borrado=0 AND p.nro_historia_clinica LIKE :num";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":num",$nro);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_consultas = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_consultas_historia");
            }

        }
        $conexion = null;
        return $total_consultas;
    }
    public function obtener_numero_consultas_numero($nro)
    {
        $total_consultas = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM consulta c INNER JOIN paciente p ON(c.paciente_id=p.id) 
                WHERE c.borrado=0 AND p.numero LIKE :num";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":num",$nro);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_consultas = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_consultas_numero");
            }

        }
        $conexion = null;
        return $total_consultas;
    }
    public function obtener_numero_consultas_doc($tipo_doc,$doc)
    {
        $total_consultas = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM consulta c INNER JOIN paciente p ON(c.paciente_id=p.id) 
                WHERE c.borrado=0 AND p.tipo_doc_id=:tipo_doc AND p.numero LIKE :doc";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":tipo_doc",$tipo_doc);
                $sentencia->bindParam(":doc",$doc);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_consultas = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_consultas_doc");
            }

        }
        $conexion = null;
        return $total_consultas;
    }

    public function insertar_consulta($paciente_id, $fecha, $motivo_id, $derivacion_id, $articulacion_con_instituciones,
        $internacion, $diagnostico, $observaciones, $tratamiento_farmacologico_id, $acompanamiento_id) {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "INSERT INTO consulta (paciente_id,fecha,motivo_id,derivacion_id,articulacion_con_instituciones,
                internacion,diagnostico,observaciones,tratamiento_farmacologico_id,acompanamiento_id,borrado) VALUES
                (:paciente_id,:fecha,:motivo_id,:derivacion_id,:articulacion_con_institucion,
                :internacion,:diagnostico,:observaciones,:tratamiento_farmacologico_id,:acompanamiento_id,0)";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":paciente_id", $paciente_id);
                $sentencia->bindParam(":fecha", $fecha);
                $sentencia->bindParam(":motivo_id", $motivo_id);
                $derivacion_id = $this->toNull($derivacion_id);
                $sentencia->bindParam(":derivacion_id", $derivacion_id);
                $sentencia->bindParam(":articulacion_con_institucion", $articulacion_con_instituciones);
                $sentencia->bindParam(":internacion", $internacion);
                $sentencia->bindParam(":diagnostico", $diagnostico);
                $sentencia->bindParam(":observaciones", $observaciones);
                $tratamiento_farmacologico_id = $this->toNull($tratamiento_farmacologico_id);
                $acompanamiento_id = $this->toNull($acompanamiento_id);
                $sentencia->bindParam(":tratamiento_farmacologico_id", $tratamiento_farmacologico_id);
                $sentencia->bindParam(":acompanamiento_id", $acompanamiento_id);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error repositorioConsulta->insertar_consulta " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }
    public function actualizar_consulta($id, $paciente_id, $fecha, $motivo_id, $derivacion_id, $articulacion_con_instituciones,
        $internacion, $diagnostico, $observaciones, $tratamiento_farmacologico_id, $acompanamiento_id) {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE consulta SET paciente_id=:paciente_id, fecha=:fecha, motivo_id=:motivo_id
                , derivacion_id=:derivacion_id, articulacion_con_instituciones=:articulacion_con_institucion,
                 internacion=:internacion, diagnostico=:diagnostico, observaciones=:observaciones, tratamiento_farmacologico_id=:tratamiento_farmacologico_id,
                 acompanamiento_id=:acompanamiento_id
                WHERE id=:id AND borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->bindParam(":paciente_id", $paciente_id);
                $sentencia->bindParam(":fecha", $fecha);
                $sentencia->bindParam(":motivo_id", $motivo_id);
                $derivacion_id = $this->toNull($derivacion_id);
                $sentencia->bindParam(":derivacion_id", $derivacion_id);
                $sentencia->bindParam(":articulacion_con_institucion", $articulacion_con_instituciones);
                $sentencia->bindParam(":internacion", $internacion);
                $sentencia->bindParam(":diagnostico", $diagnostico);
                $sentencia->bindParam(":observaciones", $observaciones);
                $tratamiento_farmacologico_id = $this->toNull($tratamiento_farmacologico_id);
                $acompanamiento_id = $this->toNull($acompanamiento_id);
                $sentencia->bindParam(":tratamiento_farmacologico_id", $tratamiento_farmacologico_id);
                $sentencia->bindParam(":acompanamiento_id", $acompanamiento_id);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error repositorioConsulta->actualizar_consulta " . $ex->getMessage());
            }

        }
    }
    public function obtener_por_id($id)
    {
        $consulta = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try{
                $sql="SELECT * FROM consulta WHERE id=:id AND borrado=0";
                $sentencia=$conexion->prepare($sql);
                $sentencia->bindParam(":id",$id);
                $sentencia->execute();
                $re=$sentencia->fetch();
                if(!empty($re)){
                    $consulta=new Consulta($re['id'],$re['paciente_id'],$re['fecha'],$re['motivo_id'],$re['derivacion_id'],
                    $re['articulacion_con_instituciones'],$re['internacion'],$re['diagnostico'],$re['observaciones'],$re['tratamiento_farmacologico_id'],
                    $re['acompanamiento_id'],$re['borrado']);
                }
            }catch(PDOException $ex){
                throw new Exception("erro consulta repositorioConsutla->obtener_por_id ".$ex->getMessage());
            }

        }
        $conexion=null;
        return $consulta;
    }
    public function obtener_info_completa($id){
        /*
        id
        paciente_id
        nombre
        apellido
        fecha
        motivo
        institucion
        articulacion_con_institucion
        internacion
        diagnostico
        observaciones
        tratamiento_Farmacologico
        acompanamiento
        tipo_documento
        documento
        historia_clinica
        */
        $resultado = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try{
                $sql="SELECT Distinct c.id,c.paciente_id,p.nombre as nombre,p.apellido as apellido,c.fecha,m.nombre as motivo,i.nombre as institucion,c.articulacion_con_instituciones,c.internacion,
                c.diagnostico,c.observaciones,tf.nombre as tratamiento_farmacologico,a.nombre as acompanamiento,td.nombre as tipo_documento,p.numero as documento,
                p.nro_historia_clinica as historia_clinica
                FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                                LEFT JOIN institucion i ON (c.derivacion_id=i.id)
                                LEFT JOIN tratamiento_farmacologico tf ON(c.tratamiento_farmacologico_id=tf.id)
                                LEFT JOIN acompanamiento a ON(c.acompanamiento_id=a.id)
                                INNER JOIN paciente p ON(c.paciente_id=p.id)
                                LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                WHERE c.id=:id AND c.borrado=0";
                $sentencia=$conexion->prepare($sql);
                $sentencia->bindParam(":id",$id);
                $sentencia->execute();
                $resultado=$sentencia->fetch(PDO::FETCH_ASSOC);
            }catch(PDOExeption $ex){
                throw new Exception ("error obtener_info_completa ".$ex->getMessage());
            }
    }
    $conexion=null;
    return $resultado;
}
    public function eliminar_consulta($id){
        $ok=false;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="UPDATE consulta SET borrado=1 WHERE id=:id";
                $s=$conexion->prepare($sql);
                $s->bindParam(":id",$id);
                $ok=$s->execute();
            }catch(PDOException $ex){
                throw new Exception ("error consulta eliminar_consulta ".$ex->getMessage());
            }
        }
        $conexion=null;
        return $ok;
    }
    public function eliminar_consultas_id_paciente($id){
        $ok=false;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="UPDATE consulta SET borrado=1 WHERE paciente_id=:id";
                $s=$conexion->prepare($sql);
                $s->bindParam(":id",$id);
                $ok=$s->execute();
            }catch(PDOException $ex){
                throw new Exception ("error consulta eliminar_consultas_id_paciente ".$ex->getMessage());
            }
        }
        $conexion=null;
        return $ok;
    }
    public function obtener_todos_limite_pagina($limite, $pag)
    /*
     tipo_documento
     documento
     historia_clinica
     fecha
     motivo
     internacion
     */
    {$result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql="SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
                c.fecha,m.nombre as motivo,c.internacion
                FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                                INNER JOIN paciente p ON(c.paciente_id=p.id)
                                LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                WHERE c.borrado=0
                LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $consultas[] = array("id"=>$r["id"],"tipo_documento"=>$r["tipo_documento"],"documento"=>$r["documento"],"historia_clinica"=>$r["historia_clinica"],
                        "fecha"=>$r["fecha"],"motivo"=>$r["motivo"],"internacion"=>$r["internacion"]);

                    }
                    $result['consultas'] = $consultas;
                    $result['total_consultas'] = $this->obtener_numero_consultas();
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioconsultas->obtener_todos_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function obtener_historia_limite_pagina($nro,$limite, $pag)
    {$result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        $conexion = abrir_conexion();
        $nro="%".$nro."%";
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql="SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
                c.fecha,m.nombre as motivo,c.internacion
                FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                                INNER JOIN paciente p ON(c.paciente_id=p.id)
                                LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                WHERE c.borrado=0 AND p.nro_historia_clinica LIKE :nro
                LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":nro",$nro); 
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $consultas[] = array("id"=>$r["id"],"tipo_documento"=>$r["tipo_documento"],"documento"=>$r["documento"],"historia_clinica"=>$r["historia_clinica"],
                        "fecha"=>$r["fecha"],"motivo"=>$r["motivo"],"internacion"=>$r["internacion"]);

                    }
                    $result['consultas'] = $consultas;
                    $result['total_consultas'] = $this->obtener_numero_consultas_historia($nro);
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioconsultas->obtener_historia_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function obtener_numero_limite_pagina($nro,$limite, $pag)
    {$result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        $conexion = abrir_conexion();
        $nro="%".$nro."%";
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql="SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
                c.fecha,m.nombre as motivo,c.internacion
                FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                                INNER JOIN paciente p ON(c.paciente_id=p.id)
                                LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                WHERE c.borrado=0 AND p.numero LIKE :nro
                LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":nro",$nro); 
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $consultas[] = array("id"=>$r["id"],"tipo_documento"=>$r["tipo_documento"],"documento"=>$r["documento"],"historia_clinica"=>$r["historia_clinica"],
                        "fecha"=>$r["fecha"],"motivo"=>$r["motivo"],"internacion"=>$r["internacion"]);

                    }
                    $result['consultas'] = $consultas;
                    $result['total_consultas'] = $this->obtener_numero_consultas_numero($nro);
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioconsultas->obtener_numero_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function obtener_documento_limite_pagina($tipo_doc,$doc,$limite, $pag)
    {$result = array();
        $result['consultas'] = array();
        $result['total_consultas'] = 0;
        $consultas = array();
        $conexion = abrir_conexion();
        $doc="%".$doc."%";
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql="SELECT c.id, td.nombre as tipo_documento,p.numero as documento,p.nro_historia_clinica as historia_clinica,
                c.fecha,m.nombre as motivo,c.internacion
                FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                                INNER JOIN paciente p ON(c.paciente_id=p.id)
                                LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                WHERE c.borrado=0 AND p.tipo_doc_id=:tipo_doc AND p.numero LIKE :num
                LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":tipo_doc",$tipo_doc);
                $sentencia->bindParam(":num",$doc);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $consultas[] = array("id"=>$r["id"],"tipo_documento"=>$r["tipo_documento"],"documento"=>$r["documento"],"historia_clinica"=>$r["historia_clinica"],
                        "fecha"=>$r["fecha"],"motivo"=>$r["motivo"],"internacion"=>$r["internacion"]);

                    }
                    $result['consultas'] = $consultas;
                    $result['total_consultas'] = $this->obtener_numero_consultas_doc($tipo_doc,$doc);
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioconsultas->obtener_documento_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function genero_porcentaje(){
        $result=array();
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="SELECT distinct g.nombre as nombre ,COUNT(g.nombre) as cantidad 
                    FROM consulta c INNER JOIN paciente p ON (c.paciente_id=p.id)
                                    INNER JOIN genero g ON (p.genero_id=g.id)
                    WHERE c.borrado=0
                    GROUP BY g.nombre";
                 $s=$conexion->prepare($sql);
                 $s->execute();
                 $re=$s->fetchAll();
                 $total=$this->obtener_numero_consultas();
                 $cant=0;
                 foreach($re as $r){
                     $porcentaje=($r['cantidad']*100)/$total;
                     $porcentaje=bcdiv($porcentaje,'1',2);
                     $result[]=array("nombre"=>$r['nombre'],"porcentaje"=> $porcentaje);
                     $cant=$cant+$r['cantidad'];
                 }
                 if($cant<$total){
                     $porcentaje=((($total-$cant)*100)/$total);
                     $porcentaje=bcdiv($porcentaje,'1',2);
                     $result[]=array("nombre"=>"no se conoce","porcentaje"=>$porcentaje);
                 }   
            }catch(PDOException $e){
                throw new Exception("error genero_porcentaje ".$e->getMessage());
            }
        }
        $conexion=null;
        return $result;
    }
    public function motivo_porcentaje(){
        $result=array();
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="SELECT distinct m.nombre as nombre ,COUNT(m.nombre) as cantidad 
                    FROM consulta c INNER JOIN motivo_consulta m ON (c.motivo_id=m.id)
                    WHERE c.borrado=0
                    GROUP BY m.nombre";
                 $s=$conexion->prepare($sql);
                 $s->execute();
                 $re=$s->fetchAll();
                 $total=$this->obtener_numero_consultas();
                 $cant=0;
                 foreach($re as $r){
                     $porcentaje=($r['cantidad']*100)/$total;
                     $porcentaje=bcdiv($porcentaje,'1',2);
                     $result[]=array("nombre"=>$r['nombre'],"porcentaje"=> $porcentaje);
                 }
                  
            }catch(PDOException $e){
                throw new Exception("error motivo_porcentaje ".$e->getMessage());
            }
        }
        $conexion=null;
        return $result;
    }
    public function localidad_porcentaje(){
        $result=array();
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="SELECT distinct l.nombre as nombre ,COUNT(l.nombre) as cantidad 
                    FROM consulta c INNER JOIN paciente p ON (c.paciente_id=p.id)
                                    INNER JOIN localidad l ON (p.localidad_id=l.id)
                    WHERE c.borrado=0
                    GROUP BY l.nombre";
                 $s=$conexion->prepare($sql);
                 $s->execute();
                 $re=$s->fetchAll();
                 $total=$this->obtener_numero_consultas();
                 $cant=0;
                 foreach($re as $r){
                     $porcentaje=($r['cantidad']*100)/$total;
                     $porcentaje=bcdiv($porcentaje,'1',2);
                     $result[]=array("nombre"=>$r['nombre'],"porcentaje"=> $porcentaje);
                     $cant=$cant+$r['cantidad'];
                 }
                 if($cant<$total){
                     $porcentaje=((($total-$cant)*100)/$total);
                     $porcentaje=bcdiv($porcentaje,'1',2);
                     $result[]=array("nombre"=>"no se conoce localidad","porcentaje"=>$porcentaje);
                 }   
            }catch(PDOException $e){
                throw new Exception("error localidad_porcentaje ".$e->getMessage());
            }
        }
        $conexion=null;
        return $result;
    }
}

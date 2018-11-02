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

    public function insertar_consulta($paciente_id, $fecha, $motivo_id, $derivacion_id, $articulacion_con_instituciones,
        $internacion, $diagnostico, $observaciones, $tratamiento_farmacologico_id, $acompanamiento_id) {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "INSERT INTO consulta (paciente_id,fecha,motivo_id,derivacion_id,articulacion_con_instituciones,
                internacion,diagnostico,observaciones,tratamiento_farmacologico_id,acompanamiento_id) VALUES
                (:paciente_id,:fecha,:motivo_id,:derivacion_id,:articulacion_con_institucion,
                :internacion,:diagnostico,:observaciones,:tratamiento_farmacologico_id,:acompanamiento_id)";
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
                WHERE id=:id";
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
                $sql="SELECT * FROM consulta WHERE id=:id";
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
        $resultado = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try{
                $sql="SELECT Distinct c.id,c.paciente_id,c.fecha,m.nombre as motivo,i.nombre as institucion,c.articulacion_con_instituciones,c.internacion,
                c.diagnostico,c.observaciones,tf.nombre as tratamiento_farmacologico,a.nombre as acompanamiento
                FROM consulta c LEFT JOIN motivo_consulta m ON(c.motivo_id=m.id)
                                LEFT JOIN institucion i ON (c.derivacion_id=i.id)
                                LEFT JOIN tratamiento_farmacologico tf ON(c.tratamiento_farmacologico_id=tf.id)
                                LEFT JOIN acompanamiento a ON(c.acompanamiento_id=a.id)
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
}

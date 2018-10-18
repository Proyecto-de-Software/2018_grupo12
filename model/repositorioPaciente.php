<?php
/*include_once "paciente.php";
include_once "conexion.php";
*/
class RepositorioPaciente
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function insertar_paciente($paciente)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "INSERT INTO paciente(apellido,nombre,fecha_nac,lugar_nac,localidad_id,partido_id,region_sanitaria_id,
            domicilio,genero_id,tiene_documento,tipo_doc_id,numero,tel,nro_historia_clinica,nro_carpeta,obra_social_id,borrado)
            VALUES (:apellido,:nombre,:fecha_nac,:lugar_nac,:localidad_id,:partido_id,:region_sanitaria_id,:domicilio,
            :genero_id,:tiene_documento,:tipo_doc_id,:numero,:tel,:nro_historia_clinica,:nro_carpeta,:obra_social_id,0)";
                $sentencia = $conexion->prepare($sql);
                $obApellido = $paciente->getApellido();
                $obNombre = $paciente->getNombre();
                $obFecha_nac = $paciente->getFecha_nac();
                $obLugar_nac = $paciente->getLugar_nac();
                $obLocalidad_id = $paciente->getLocalidad_id();
                $obPartido_id =$paciente ->getPartido_id();
                $obRegion_sanitaria_id = $paciente->getRegion_sanitaria_id();
                $obDomicilio = $paciente->getDomicilio();
                $obGenero_id = $paciente->getGenero_id();
                $obTiene_documento = $paciente->getTiene_documento();
                $obTipo_doc_id = $paciente->getTipo_doc_id();
                $obNumero = $paciente->getNumero();
                $obTel = $paciente->getTel();
                $obNro_historia_clinica = $paciente->getNro_historia_clinica();
                $obNro_carpeta = $paciente->getNro_carpeta();
                $obObra_social_id = $paciente->getObra_social_id();
                $sentencia->bindParam(":apellido", $obApellido);
                $sentencia->bindParam(":nombre", $obNombre);
                $sentencia->bindParam(":fecha_nac", $obFecha_nac);
                $sentencia->bindParam(":lugar_nac", $obLugar_nac);
                $sentencia->bindParam(":localidad_id", $obLocalidad_id);
                $sentencia->bindParam(":partido_id",$obPartido_id);
                $sentencia->bindParam(":region_sanitaria_id", $obRegion_sanitaria_id);
                $sentencia->bindParam(":domicilio", $obDomicilio);
                $sentencia->bindParam(":genero_id", $obGenero_id);
                $sentencia->bindParam(":tiene_documento", $obTiene_documento);
                $sentencia->bindParam(":tipo_doc_id", $obTipo_doc_id);
                $sentencia->bindParam(":numero", $obNumero);
                $sentencia->bindParam(":tel", $obTel);
                $sentencia->bindParam(":nro_historia_clinica", $obNro_historia_clinica);
                $sentencia->bindParam(":nro_carpeta", $obNro_carpeta);
                $sentencia->bindParam(":obra_social_id", $obObra_social_id);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta insertar_paciente " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;

    }
    public function insertar_nn($nro_historia_clinica){
     $ok=false;
     $conexion=abrir_conexion();
     if($conexion !==null){
         try{
            $sql = "INSERT INTO paciente(apellido,nombre,fecha_nac,lugar_nac,localidad_id,partido_id,region_sanitaria_id,
            domicilio,genero_id,tiene_documento,tipo_doc_id,numero,tel,nro_historia_clinica,nro_carpeta,obra_social_id,borrado)
            VALUES ('NN','NN',0001-01-01,' ',null,null,null,' ',
            null,0,null,0,' ',:nro_historia_clinica,0,null,0)";
            $sentencia=$conexion->prepare($sql);
            $sentencia->bindParam(":nro_historia_clinica",$nro_historia_clinica);
            $ok=$sentencia->execute();
         }catch(PDOException $ex){
             throw new Exception("error consulta insertar NN ".$ex->getMessage());
         }
     }
    $conexion=null;
    return $ok;

    }
    public function eliminar_paciente($id)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE paciente SET borrado=1 WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $ok = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("error consulta eliminar_paciente " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }

    public function actualizar_informacion($id,$apellido,$nombre,$fecha_nac,$lugar_nac,$localidad_id,$partido_id,
    $region_sanitaria_id,$domicilio,$genero_id,$tiene_documento,$tipo_doc_id,$numero,$tel,
    $nro_historia_clinica,$nro_carpeta,$obra_social_id)
    {
     $ok=false;
     $conexion=abrir_conexion();
     if($conexion !==null){
         try{
             $sql= "UPDATE paciente SET apellido=:apellido ,nombre=:nombre, fecha_nac=:fecha_nac,lugar_nac=:lugar_nac,
              localidad_id=:localidad_id,partido_id=:partido_id, region_sanitaria_id=:region_sanitaria_id, domicilio=:domicilio,
               genero_id=:genero_id, tiene_documento=:tiene_documento, tipo_doc_id=:tipo_doc_id,
                numero=:numero, tel=:tel, nro_historia_clinica=:nro_historia_clinica,nro_carpeta=:nro_carpeta,
                obra_social_id=:obra_social_id
                WHERE id=:id ";
             $sentencia=$conexion->prepare($sql);
             $sentencia->bindParam(":id",$id);
             $sentencia->bindParam(":apellido",$apellido);
             $sentencia->bindParam(":nombre",$nombre);
             $sentencia->bindParam(":fecha_nac",$fecha_nac);
             $sentencia->bindParam(":lugar_nac",$lugar_nac);
             if($localidad_id==""||$localidad_id==0){
                 $localidad_id=null;
             }
             $sentencia->bindParam(":localidad_id",$localidad_id);
             if($partido_id==""||$partido_id==0){
                $partido_id=null;
            }
             $sentencia->bindParam(":partido_id",$partido_id);
             if($region_sanitaria_id==""||$region_sanitaria_id==0){
                $region_sanitaria_id=null;
            }
             $sentencia->bindParam(":region_sanitaria_id",$region_sanitaria_id);         
             $sentencia->bindParam(":domicilio",$domicilio);
             if($genero_id==""||$genero_id==0){
                 $genero_id=null;
             }
             $sentencia->bindParam(":genero_id",$genero_id);
             $sentencia->bindParam(":tiene_documento",$tiene_documento);
             if($tipo_doc_id==""||$tipo_doc_id==0){
                 $tipo_doc_id=null;
             }
             $sentencia->bindParam(":tipo_doc_id",$tipo_doc_id);
             $sentencia->bindParam(":numero",$numero);
             $sentencia->bindParam(":tel",$tel);
             $sentencia->bindParam(":nro_historia_clinica",$nro_historia_clinica);
             $sentencia->bindParam(":nro_carpeta",$nro_carpeta);
             if($obra_social_id==""||$obra_social_id==0){
                 $obra_social_id=null;
             }
             $sentencia->bindParam(":obra_social_id",$obra_social_id);
             $ok=$sentencia->execute();
         }catch(PDOException $ex){
             throw new Exception ("erro consulta repositorioPaciente->actualizar_informacion ".$ex->getMessage());
         }
     }
     $conexion=null;
     return $ok;

    }

    public function obtener_por_nro_historia_clinica($nro)
    { /*si no existe devuelve null */
        $paciente = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM paciente WHERE nro_historia_clinica=:nro AND borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":nro", $nro);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $paciente = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_nro_historia_clinica " . $ex->getMessage());

            }
        }
        $conexion = null;
        return $paciente;
    }

    public function obtener_por_datos_paciente($nombre, $apellido, $tipo_doc, $num)
    { /*si no existe devuelve null */
        $paciente = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM paciente WHERE nombre=:nombre AND apellido=:apellido AND tipo_doc_id=:tipo_doc_id AND numero=:num AND borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":nombre", $nombre);
                $sentencia->bindParam(":apellido", $apellido);
                $sentencia->bindParam(":tipo_doc_id", $tipo_doc);
                $sentencia->bindParam(":num", $num);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $paciente = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_datos_paciente " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $paciente;
    }
    public function obtener_por_nombre($nombre,$limite,$pag)
    { /*si no existe devuelve null */
        $paciente = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $nombre= "%".$nombre."%";
                $sql = "SELECT * FROM paciente WHERE nombre LIKE :nombre  AND borrado=0 LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":nombre", $nombre);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $re = $sentencia->fetchAll();
                if (count($re)) {
                    foreach($re as $r){
                    $paciente[] = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);
                }}
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_nombre " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $paciente;
    }
    public function obtener_por_apellido($apellido,$limite,$pag)
    { /*si no existe devuelve null */
        $paciente = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $apellido= "%".$apellido."%";
                $sql = "SELECT * FROM paciente WHERE apellido LIKE :apellido  AND borrado=0 LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":apellido", $apellido);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $re = $sentencia->fetchAll();
                if (count($re)) {
                    foreach($re as $r){
                    $paciente[] = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);
                }}
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_apellido" . $ex->getMessage());
            }
        }
        $conexion = null;
        return $paciente;
    }
    public function obtener_por_nombre_y_apellido($nombre,$apellido,$limite,$pag)
    { /*si no existe devuelve null */
        $paciente = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $nombre= "%".$nombre."%";
                $apellido= "%".$apellido."%";
                $sql = "SELECT * FROM paciente WHERE nombre LIKE :nombre AND apellido LIKE :apellido AND borrado=0 LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":nombre", $nombre);
                $sentencia->bindParam(":apellido",$apellido);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $re = $sentencia->fetchAll();
                if (count($re)) {
                    foreach($re as $r){
                    $paciente[] = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);
                }}
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_nombre_y_apellido " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $paciente;
    }
    public function obtener_por_datos_doc( $tipo_doc, $num)
    { /*si no existe devuelve null */
        $paciente = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM paciente WHERE  tipo_doc_id=:tipo_doc_id AND numero=:num AND borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":tipo_doc_id", $tipo_doc);
                $sentencia->bindParam(":num", $num);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $paciente = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_datos_doc " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $paciente;
    }
    public function obtener_por_id($id)
    { /*si no existe devuelve null */
        $paciente = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM paciente WHERE id=:nro AND borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":nro", $id);
                $sentencia->execute();
                $r = $sentencia->fetch();
                if (!empty($r)) {
                    $paciente = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_id " . $ex->getMessage());

            }
        }
        $conexion = null;
        return $paciente;
    }
    public function obtener_por_id_info_completa($id)
    {
        /*devuelve un arreglo con toda la info de un paciente (clave=valor)
        claves del arreglo=
        id
        apellido
        nombre
        fecha_nac
        lugar_nac
        localidad
        region_sanitaria
        domicilio
        genero
        tiene_documento
        tipo_doc
        numero
        tel
        nro_historia_clinica
        nro_carpeta
        obra_social
        borrado*/
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT Distinct p.id,p.apellido,p.nombre,p.fecha_nac,p.lugar_nac, l.nombre as localidad, pa.nombre as partido , r.nombre as region_sanitaria,p.domicilio,
                g.nombre as genero,p.tiene_documento,td.nombre as tipo_doc, p.numero,p.tel,p.nro_historia_clinica,p.nro_carpeta,o.nombre as obra_social,p.borrado
                FROM paciente p LEFT JOIN localidad l ON(p.localidad_id=l.id)
                                LEFT JOIN partido pa ON(p.partido_id= pa.id)
                                LEFT JOIN region_sanitaria r ON(p.region_sanitaria_id=r.id)
                                LEFT JOIN genero g ON(p.genero_id=g.id)
                                LEFT JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                                LEFT JOIN obra_social o ON(p.obra_social_id=o.id)
                WHERE p.id=:id AND borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $r = $sentencia->fetch(PDO::FETCH_ASSOC);
                if (empty($r)) {
                    return null;
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_por_id_info_completa " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $r;
    }
    public function obtener_todos_limite_pagina($limite, $pag)
    {
        $pacientes = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql = "SELECT * FROM paciente WHERE borrado=0  LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $r) {
                        $pacientes[] = new Paciente($r["id"], $r["apellido"], $r["nombre"], $r["fecha_nac"], $r["lugar_nac"], $r["localidad_id"],$r["partido_id"],
                        $r["region_sanitaria_id"], $r["domicilio"], $r["genero_id"], $r["tiene_documento"], $r["tipo_doc_id"], $r["numero"], $r["tel"],
                        $r["nro_historia_clinica"], $r["nro_carpeta"], $r["obra_social_id"], $r["borrado"]);

                    }
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta repositorioPacientes->obtener_todos_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $pacientes;
    }

}

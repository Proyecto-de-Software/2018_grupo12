<?php
include_once "paciente.php";
include_once "conexion.php";

class RepositorioPaciente{

    public function insertar_paciente($paciente){
        $ok=false;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
            $sql="INSERT INTO paciente(apellido,nombre,fecha_nac,lugar_nac,localidad_id,region_sanitaria_id,
            domicilio,genero_id,tiene_documento,tipo_doc_id,numero,tel,nro_historia_clinica,nro_carpeta,obra_social_id,borrado)
            VALUES (:apellido,:nombre,:fecha_nac,:lugar_nac,:localidad_id,:region_sanitaria_id,:domicilio,
            :genero_id,:tiene_documento,:tipo_doc_id,:numero,:tel,:nro_historia_clinica,:nro_carpeta,:obra_social_id,0)";
            $sentencia=$conexion->prepare($sql);
            $obApellido=$paciente->getApellido();
            $obNombre=$paciente->getNombre();
            $obFecha_nac=$paciente->getFecha_nac();
            $obLugar_nac=$paciente->getLugar_nac();
            $obLocalidad_id=$paciente->getLocalidad_id();
            $obRegion_sanitaria_id=$paciente->getRegion_sanitaria_id();
            $obDomicilio=$paciente->getDomicilio();
            $obGenero_id=$paciente->getGenero_id();
            $obTiene_documento=$paciente->getTiene_documento();
            $obTipo_doc_id=$paciente->getTipo_doc_id();
            $obNumero=$paciente->getNumero();
            $obTel=$paciente->getTel();
            $obNro_historia_clinica=$paciente->getNro_historia_clinica();
            $obNro_carpeta=$paciente->getNro_carpeta();
            $obObra_social_id=$paciente->getObra_social_id();
            $sentencia ->bindParam(":apellido",$obApellido);
            $sentencia ->bindParam(":nombre",$obNombre);
            $sentencia ->bindParam(":fecha_nac",$obFecha_nac);
            $sentencia ->bindParam(":lugar_nac",$obLugar_nac);
            $sentencia ->bindParam(":localidad_id",$obLocalidad_id);
            $sentencia ->bindParam(":region_sanitaria_id",$obRegion_sanitaria_id);
            $sentencia ->bindParam(":domicilio",$obDomicilio);
            $sentencia ->bindParam(":genero_id",$obGenero_id);
            $sentencia ->bindParam(":tiene_documento",$obTiene_documento);
            $sentencia ->bindParam(":tipo_doc_id",$obTipo_doc_id);
            $sentencia ->bindParam(":numero",$obNumero);
            $sentencia ->bindParam(":tel",$obTel);
            $sentencia ->bindParam(":nro_historia_clinica",$obNro_historia_clinica);
            $sentencia ->bindParam(":nro_carpeta",$obNro_carpeta);
            $sentencia ->bindParam(":obra_social_id",$obObra_social_id);
            $ok=$sentencia->execute();
        }catch(PDOException $ex){
            throw new Exception("error consulta insertar_paciente ".$ex->getMessage());
        }
    }return $ok;

    }
    public function eliminar_paciente($id){
        $ok=false;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="UPDATE paciente SET borrado=1 WHERE id=:id";
                $sentencia=$conexion->prepare($sql);
                $sentencia->bindParam(":id",$id);
                $ok=$sentencia->execute();

            }catch(PDOException $ex){
                throw new Exception("error consulta eliminar_paciente ".$ex->getMessage());
            }
        }
        return $ok;
    }

    public function actualizar_informacion(){}/*preguntar que informacion se actualiza */

    
    public function obtener_por_nro_historia_clinica($nro){ /*si no existe devuelve null */
        $paciente=null;
        $conexion=abrir_conexion();
        if($conexion !==null){
            try{
                $sql="SELECT * FROM paciente WHERE nro_historia_clinica=:nro AND borrado=0";
                $sentencia=$conexion->prepare($sql);
                $sentencia->bindParam(":nro",$nro);
                $sentencia->execute();
                $r=$sentencia->fetch();
                if(!empty($r)){
                $paciente= new Paciente($r["id"],$r["apellido"],$r["nombre"],$r["fecha_nac"],$r["lugar_nac"],$r["localidad_id"],
                $r["region_sanitaria_id"],$r["domicilio"],$r["genero_id"],$r["tiene_documento"],$r["tipo_doc_id"],$r["numero"],$r["tel"],
                $r["nro_historia_clinica"],$r["nro_carpeta"],$r["obra_social_id"],$r["borrado"]);
                }
            }catch(PDOException $ex){
                throw new Exception("error consulta obtener_por_nro_historia_clinica ".$ex->getMessage());

            }
        }
        return $paciente;
    }
    
    public function obtener_por_datos_paciente($nombre,$apellido,$tipo_doc,$num){/*si no existe devuelve null */
        $paciente=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="SELECT * FROM paciente WHERE nombre=:nombre AND apellido=:apellido AND tipo_doc_id=:tipo_doc_id AND numero=:num AND borrado=0";
                $sentencia=$conexion->prepare($sql);
                $sentencia->bindParam(":nombre",$nombre);
                $sentencia->bindParam(":apellido",$apellido);
                $sentencia->bindParam(":tipo_doc_id",$tipo_doc);
                $sentencia->bindParam(":num",$num);
                $sentencia->execute();
                $r=$sentencia->fetch();
                if(!empty($r)){
                $paciente= new Paciente($r["id"],$r["apellido"],$r["nombre"],$r["fecha_nac"],$r["lugar_nac"],$r["localidad_id"],
                $r["region_sanitaria_id"],$r["domicilio"],$r["genero_id"],$r["tiene_documento"],$r["tipo_doc_id"],$r["numero"],$r["tel"],
                $r["nro_historia_clinica"],$r["nro_carpeta"],$r["obra_social_id"],$r["borrado"]);
                }
            }catch(PDOException $ex){
                throw new Exception("error consulta obtener_por_datos_paciente ".$ex->getMessage());
            }
        }
        return $paciente;
    }
    public function obtener_por_id($id){ /*si no existe devuelve null */
        $paciente=null;
        $conexion=abrir_conexion();
        if($conexion !==null){
            try{
                $sql="SELECT * FROM paciente WHERE id=:nro AND borrado=0";
                $sentencia=$conexion->prepare($sql);
                $sentencia->bindParam(":nro",$id);
                $sentencia->execute();
                $r=$sentencia->fetch();
                if(!empty($r)){
                $paciente= new Paciente($r["id"],$r["apellido"],$r["nombre"],$r["fecha_nac"],$r["lugar_nac"],$r["localidad_id"],
                $r["region_sanitaria_id"],$r["domicilio"],$r["genero_id"],$r["tiene_documento"],$r["tipo_doc_id"],$r["numero"],$r["tel"],
                $r["nro_historia_clinica"],$r["nro_carpeta"],$r["obra_social_id"],$r["borrado"]);
                }
            }catch(PDOException $ex){
                throw new Exception("error consulta obtener_por_id ".$ex->getMessage());

            }
        }
        return $paciente;
    }
    public function obtener_por_id_info_completa($id){
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
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql="SELECT p.id,p.apellido,p.nombre,p.fecha_nac,p.lugar_nac, l.nombre as localidad, r.nombre as region_sanitaria,p.domicilio,
                g.nombre as genero,p.tiene_documento,td.nombre as tipo_doc, p.numero,p.tel,p.nro_historia_clinica,p.nro_carpeta,o.nombre as obra_social,p.borrado
                FROM paciente p INNER JOIN localidad l ON(p.localidad_id=l.id)
                                INNER JOIN region_sanitaria r ON(p.region_sanitaria_id=r.id)
                                INNER JOIN genero g ON(p.genero_id=g.id)
                                INNER JOIN tipo_documento td ON(p.tipo_doc_id=td.id)
                                INNER JOIN obra_social o ON(p.obra_social_id=o.id)
                WHERE p.id=:id AND borrado=0";
                $sentencia=$conexion->prepare($sql);
                $sentencia->bindParam(":id",$id);
                $sentencia->execute();
                $r=$sentencia->fetch();
                if(empty($r)){
                    return null;
                }                
            }catch(PDOException $ex){
                throw new Exception("error consulta obtener_por_id_info_completa ".$ex->getMessage());
            }
        }
        return $r;
    }


}
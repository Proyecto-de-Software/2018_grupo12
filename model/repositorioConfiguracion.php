<?php

include_once "conexion.php";

class RepositorioConfiguracion
/*id 1=titulo
  id 2=descripcion
  id 3=email
  id 4=limite
  id 5= habilitado */
{

    public function setTitulo($titulo)
    {   
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE id=1";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $titulo);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set titulo " . $ex->getMessage());
            }
        }
        return $ok;
    }

    public function setDescripcion($descripcion)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE id=2";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $descripcion);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set descripción " . $ex->getMessage());
            }
        }
        return $ok;
    }
    public function setEmail($Email)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE id=3";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $Email);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set email " . $ex->getMessage());
            }
        }
        return $ok;
    }
    public function setLimite($limite)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE id=4";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $limite, PDO::PARAM_INT);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set limite " . $ex->getMessage());
            }
        }
        return $ok;
    }
    public function habilitar()
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=1 WHERE id=5";
                $sentencia = $conexion->prepare($sql);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta habilitar " . $ex->getMessage());
            }
        }
        return $ok;
    }
    public function deshabilitar()
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=0 WHERE id=5";
                $sentencia = $conexion->prepare($sql);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta deshabilitar " . $ex->getMessage());
            }
        }
        return $ok;
    }

    public function getTitulo(){
       $titulo=null;
       $conexion=abrir_conexion();
       if($conexion!==null){
           try{
               $sql ="SELECT valor FROM configuracion WHERE id=1";
               $sentencia=$conexion ->prepare($sql);
               $sentencia->execute();
               $titulo=$sentencia->fetchColumn();
           }catch(PDOException $ex){
               throw new Exception ("error consulta getTitulo ".$ex->getMessage());
           }
       }
       return $titulo;
    }
    public function getDescripcion(){
        $valor=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql ="SELECT valor FROM configuracion WHERE id=2";
                $sentencia=$conexion ->prepare($sql);
                $sentencia->execute();
                $valor=$sentencia->fetchColumn();
            }catch(PDOException $ex){
                throw new Exception ("error consulta getDescripcion ".$ex->getMessage());
            }
        }
        return $valor;
     }
     public function getEmail(){
        $valor=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql ="SELECT valor FROM configuracion WHERE id=3";
                $sentencia=$conexion ->prepare($sql);
                $sentencia->execute();
                $valor=$sentencia->fetchColumn();
            }catch(PDOException $ex){
                throw new Exception ("error consulta getEmail ".$ex->getMessage());
            }
        }
        return $valor;
     }
     public function getLimite(){
        $valor=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql ="SELECT valor FROM configuracion WHERE id=4";
                $sentencia=$conexion ->prepare($sql);
                $sentencia->execute();
                $valor=$sentencia->fetchColumn();
            }catch(PDOException $ex){
                throw new Exception ("error consulta getLimite ".$ex->getMessage());
            }
        }
        return $valor;
     }
     public function getHabilitado(){
        $valor=null;
        $conexion=abrir_conexion();
        if($conexion!==null){
            try{
                $sql ="SELECT valor FROM configuracion WHERE id=5";
                $sentencia=$conexion ->prepare($sql);
                $sentencia->execute();
                $valor=$sentencia->fetchColumn();
            }catch(PDOException $ex){
                throw new Exception ("error consulta getHabilitado ".$ex->getMessage());
            }
        }
        return $valor;
     }
     public function obtener_configuracion(){

        /*titulo,descripcion,email,limite,hiabilitado */
         $arreglo=array();
         $conexion=abrir_conexion();
         if($conexion!==null){
             try{
                 $sql="SELECT variable,valor from configuracion";
                 $sentencia=$conexion->prepare($sql);
                 $sentencia->execute();
                 $resultado=$sentencia->fetchAll();
                 if(count($resultado)){
                     foreach($resultado as $re){
                         $arreglo[$re["variable"]]=$re["valor"];
                     }
                 }
             }catch(PDOException $ex){
                 throw new Exception("errpr consulta obtener_configuracion ".$ex->getMessage());
             }
         }return $arreglo;
     }

}

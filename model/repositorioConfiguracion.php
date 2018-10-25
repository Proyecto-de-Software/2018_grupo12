<?php

class RepositorioConfiguracion
/*id 1=titulo
id 2=descripcion
id 3=email
id 4=limite
id 5= habilitado */

{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setTitulo($titulo)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE variable='titulo'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $titulo);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set titulo " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }

    public function setDescripcion($descripcion)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE variable='descripcion'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $descripcion);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set descripción " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }
    public function setEmail($Email)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE variable='email'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $Email);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set email " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }
    public function setLimite($limite)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=:valor WHERE variable='limite'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":valor", $limite, PDO::PARAM_INT);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta set limite " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }
    public function habilitar()
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=1 WHERE variable='habilitado'";
                $sentencia = $conexion->prepare($sql);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta habilitar " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }
    public function deshabilitar()
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE configuracion SET valor=0 WHERE variable='habilidato'";
                $sentencia = $conexion->prepare($sql);
                $ok = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta deshabilitar " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $ok;
    }

    public function getTitulo()
    {
        $titulo = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT valor FROM configuracion WHERE variable='titulo'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $titulo = $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                throw new Exception("error consulta getTitulo " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $titulo;
    }
    public function getDescripcion()
    {
        $valor = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT valor FROM configuracion WHERE variable='descripcion'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $valor = $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                throw new Exception("error consulta getDescripcion " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $valor;
    }
    public function getEmail()
    {
        $valor = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT valor FROM configuracion WHERE variable='email'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $valor = $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                throw new Exception("error consulta getEmail " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $valor;
    }
    public function getLimite()
    {
        $valor = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT valor FROM configuracion WHERE variable='limite'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $valor = $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                throw new Exception("error consulta getLimite " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $valor;
    }
    public function getHabilitado()
    {
        $valor = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT valor FROM configuracion WHERE variable='habilitado'";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $valor = $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                throw new Exception("error consulta getHabilitado " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $valor;
    }
    public function obtener_configuracion()
    {

        /*titulo,descripcion,email,limite,hiabilitado */
        $arreglo = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT variable,valor from configuracion";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $re) {
                        $arreglo[$re["variable"]] = $re["valor"];
                    }
                }
            } catch (PDOException $ex) {
                throw new Exception("errpr consulta obtener_configuracion " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $arreglo;
    }

}

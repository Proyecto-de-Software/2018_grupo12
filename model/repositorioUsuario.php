<?php

include_once "usuario.php";
include_once "conexion.php";
class RepositorioUsuario
{ /*instanciar como una clase normal y llamar a los metodos con la forma:  $repositorioUsuario -> funcion();
....ejecutar consultas dentro de un try para obtener excepciones en el catch en caso de error */
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function obtener_numero_usuarios()
    {
        $total_usuarios = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_usuarios = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_usuarios");
            }

        }
        return $total_usuarios;
    }
    public function insertar_usuario($usuario) /*recibe objeto usuario -retorna booleano*/
    {
        $usuario_insertado = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "INSERT INTO usuario(email, username, password, activo, updated_at, created_at, first_name,
                last_name, borrado)
                 VALUES(:email, :username, :password, 1,NOW() , NOW(), :first_name,:last_name, 0)";
                $sentencia = $conexion->prepare($sql);
                $obEmail = $usuario->getEmail();
                $obUsername = $usuario->getUsername();
                $obPassword = $usuario->getPassword();
                $obFirs_name = $usuario->getFirst_name();
                $obLast_name = $usuario->getLast_name();
                $sentencia->bindParam(':email', $obEmail);
                $sentencia->bindParam(':username', $obUsername);
                $sentencia->bindParam(':password', $obPassword);
                $sentencia->bindParam(':first_name', $obFirs_name);
                $sentencia->bindParam(':last_name', $obLast_name);
                $usuario_insertado = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception("error consulta insertar_usuario " . $ex->getMessage());

            }
        }
        return $usuario_insertado;
    }
    public function username_existe($username) /* recibe el username que se decea saber si existe -retorna booleano*/
    {
        $conexion = abrir_conexion();
        $email_existe = false;
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario WHERE username = :username";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':username', $username);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    $email_existe = true;
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta username_existe");
            }
        }
        return $email_existe;
    }

    public function actualizar_informacion_usuario($usuario) /*objeto usuario con info para el cambio */
    {
        $actualizado = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario SET email=:email, password=:password, first_name=:first_name, last_name=:last_name, updated_at=NOW() WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $obId = $usuario->getId();
                $obEmail = $usuario->getUsername();
                $obPassword = $usuario->getPassword();
                $obFirst_name = $usuario->getFirst_name();
                $obLast_name = $usuario->getLast_name();
                $sentencia->bindParam(":id", $obId);
                $sentencia->bindParam(":email", $obEmail);
                $sentencia->bindParam(":password", $obPassword);
                $sentencia->bindParam(":first_name", $obFirst_name);
                $sentencia->bindParam(":last_name", $obLast_name);
                $actualizado = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("erro consulta actualizar_informacion_usuario");
            }
        }
        return $actualizado;
    }

    public function obtener_usuario_por_username($username)
    { /*retorna objeto usuario correspondiente al username pasado como parametro */
        $usuario = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario WHERE username = :username AND borrado=0";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":username", $username);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                if (!empty($resultado)) {
                    $usuario = new Usuario($resultado['id'], $resultado['email'], $resultado['username'], $resultado['password'], $resultado['activo']
                        , $resultado['updated_at'], $resultado['created_at'], $resultado['first_name'], $resultado['last_name'], $resultado['borrado']);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_usuario_por_username");
            }
        }
        return $usuario;

    }
    public function obtener_usuario_por_username_act_blo($username,$activo)
    { /*retorna objeto usuario correspondiente al username pasado como parametro con eleccion activo o bloqueado*/
        $usuario = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario WHERE username = :username AND borrado=0 AND activo=:activo";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":username", $username);
                $sentencia->bindParam(":activo",$activo);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                if (!empty($resultado)) {
                    $usuario = new Usuario($resultado['id'], $resultado['email'], $resultado['username'], $resultado['password'], $resultado['activo']
                        , $resultado['updated_at'], $resultado['created_at'], $resultado['first_name'], $resultado['last_name'], $resultado['borrado']);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_usuario_por_username_act_blo");
            }
        }
        return $usuario;

    }

   
    public function bloquear_username($username) /*bloquea usuario... ¿se debe actualizar updated_at???? */
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario SET activo=0 WHERE username=:username";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":username", $username);
                $ok = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("error consulta bloquear_username");
            }
        }
        return $ok;
    }
    public function activar_username($username) /*desbloquea/activa usuario... ¿se debe actualizar updated_at???? */
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario SET activo=1 WHERE username=:username";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":username", $username);
                $ok = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("error consulta activar_username");
            }
        }
        return $ok;
    }
    public function eliminar_username($username) /*eliminar logicamente usuario */
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario SET borrado=1 WHERE username=:username";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":username", $username);
                $ok = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("error consulta eliminar_username");
            }
        }
        return $ok;
    }
    public function obtener_todos_limite_pagina($limite,$pag)
    { 
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero=$limite*($pag-1);
                $sql = "SELECT * FROM usuario WHERE borrado=0 LIMIT :primero,:limite" ;
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name'], $fila["borrado"]);

                    }
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_todos_limite_pagina ".$ex->getMessage());
            }
        }
        return $usuarios;
    }
    public function obtener_bloqueados_limite_pagina($limite,$pag)
    { 
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero=$limite*($pag-1);
                $sql = "SELECT * FROM usuario WHERE borrado=0 AND activo=0 LIMIT :primero,:limite" ;
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name'], $fila["borrado"]);

                    }
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_bloqueados_limite_pagina ".$ex->getMessage());
            }
        }
        return $usuarios;
    }
    public function obtener_activos_limite_pagina($limite,$pag)
    { 
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero=$limite*($pag-1);
                $sql = "SELECT * FROM usuario WHERE borrado=0 AND activo=1 LIMIT :primero,:limite" ;
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name'], $fila["borrado"]);

                    }
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_activos_limite_pagina ".$ex->getMessage());
            }
        }
        return $usuarios;
    }
    public function obtener_todos_limite_pagina_like($limite,$pag,$string)
    { 
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $string="%".$string."%";
                $primero=$limite*($pag-1);
                $sql = "SELECT * FROM usuario WHERE borrado=0 AND username LIKE :string LIMIT :primero,:limite" ;
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->bindParam(":string",$string);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name'], $fila["borrado"]);

                    }
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_todos_limite_pagina ".$ex->getMessage());
            }
        }
        return $usuarios;
    }
    public function obtener_actblo_limite_pagina_like($limite,$pag,$string,$activo)
    { 
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $string="%".$string."%";
                $primero=$limite*($pag-1);
                $sql = "SELECT * FROM usuario WHERE borrado=0 AND  activo=:activo AND (username LIKE :string) LIMIT :primero,:limite" ;
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->bindParam(":string",$string);
                $sentencia->bindParam(":activo",$activo);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name'], $fila["borrado"]);

                    }
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_todos_limite_pagina ".$ex->getMessage());
            }
        }
        return $usuarios;
    }

}

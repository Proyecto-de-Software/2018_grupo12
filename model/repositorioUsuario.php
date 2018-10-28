<?php

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
    public function obtener_numero_usuarios_estado($estado)
    {
        $total_usuarios = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM usuario WHERE activo=:activo";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":activo", $estado);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_usuarios = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_usuarios_estado");
            }

        }
        $conexion = null;
        return $total_usuarios;
    }
    public function obtener_numero_like($string) /*string= %algo% */
    {
        $total_usuarios = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM usuario WHERE username LIKE :string";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":string", $string);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_usuarios = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_like");
            }

        }
        $conexion = null;
        return $total_usuarios;
    }
    public function obtener_numero_like_estado($estado, $string) /*string= %algo% */
    {
        $total_usuarios = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT COUNT(*) as total FROM usuario WHERE activo=:activo AND username LIKE :string";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":activo", $estado);
                $sentencia->bindParam(":string", $string);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                $total_usuarios = $resultado['total'];

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_numero_like_estado");
            }

        }
        $conexion = null;
        return $total_usuarios;
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
        $conexion = null;
        return $total_usuarios;
    }
    public function insertar_usuario($usuario) /*recibe objeto usuario -retorna booleano*/
    {
        $usuario_insertado = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "INSERT INTO usuario(email, username, password, activo, updated_at, created_at, first_name,
                last_name)
                 VALUES(:email, :username, :password, 1,NOW() , NOW(), :first_name,:last_name)";
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
        $conexion = null;
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
        $conexion = null;
        return $email_existe;
    }

    public function actualizar_informacion_usuario($id, $email, $first_name, $last_name) /*objeto usuario con info para el cambio */
    {
        $actualizado = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario SET email=:email, first_name=:first_name, last_name=:last_name, updated_at=NOW() WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->bindParam(":email", $email);
                $sentencia->bindParam(":first_name", $first_name);
                $sentencia->bindParam(":last_name", $last_name);
                $actualizado = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("erro consulta actualizar_informacion_usuario");
            }
        }
        $conexion = null;
        return $actualizado;
    }
    public function actualizar_password_usuario($id, $password) /*objeto usuario con info para el cambio */
    {
        $actualizado = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario SET password=:password, updated_at=NOW() WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->bindParam(":password", $password);
                $actualizado = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("erro consulta actualizar_password_usuario");
            }
        }
        $conexion = null;
        return $actualizado;
    }

    public function obtener_usuario_por_id($id)
    { /*retorna objeto usuario correspondiente al id pasado como parametro */
        $usuario = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario WHERE id = :id ";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                if (!empty($resultado)) {
                    $usuario = new Usuario($resultado['id'], $resultado['email'], $resultado['username'], $resultado['password'], $resultado['activo']
                        , $resultado['updated_at'], $resultado['created_at'], $resultado['first_name'], $resultado['last_name']);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_usuario_por_id");
            }
        }
        $conexion = null;
        return $usuario;

    }
    public function obtener_usuario_por_username($username)
    { /*retorna objeto usuario correspondiente al username pasado como parametro */
        $usuario = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario WHERE username = :username ";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":username", $username);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                if (!empty($resultado)) {
                    $usuario = new Usuario($resultado['id'], $resultado['email'], $resultado['username'], $resultado['password'], $resultado['activo']
                        , $resultado['updated_at'], $resultado['created_at'], $resultado['first_name'], $resultado['last_name']);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_usuario_por_username");
            }
        }
        $conexion = null;
        return $usuario;

    }
    public function obtener_usuario_por_username_act_blo($username, $activo)
    { /*retorna objeto usuario correspondiente al username pasado como parametro con eleccion activo o bloqueado*/
        $usuario = null;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT * FROM usuario WHERE username = :username  AND activo=:activo";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":username", $username);
                $sentencia->bindParam(":activo", $activo);
                $sentencia->execute();
                $resultado = $sentencia->fetch();
                if (!empty($resultado)) {
                    $usuario = new Usuario($resultado['id'], $resultado['email'], $resultado['username'], $resultado['password'], $resultado['activo']
                        , $resultado['updated_at'], $resultado['created_at'], $resultado['first_name'], $resultado['last_name']);
                }
            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_usuario_por_username_act_blo");
            }
        }
        $conexion = null;
        return $usuario;

    }
    public function bloquear_activar($id, $accion)
    {
        $ok = false;
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "UPDATE usuario SET activo=:accion WHERE id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":accion", $accion);
                $sentencia->bindParam(":id", $id);
                $ok = $sentencia->execute();

            } catch (PDOException $ex) {
                throw new Exception("error consulta bloquear_activar");
            }
        }
        $conexion = null;
        return $ok;
    }

    /*public function bloquear_usuario($id) /*bloquea usuario... ¿se debe actualizar updated_at????
    {
    $ok = false;
    $conexion = abrir_conexion();
    if ($conexion !== null) {
    try {
    $sql = "UPDATE usuario SET activo=0 WHERE id=:id";
    $sentencia = $conexion->prepare($sql);
    $sentencia->bindParam(":id", $id);
    $ok = $sentencia->execute();

    } catch (PDOException $ex) {
    throw new Exception("error consulta bloquear_usuario");
    }
    }
    $conexion = null;
    return $ok;
    }
    public function activar_usuario($id) /*desbloquea/activa usuario... ¿se debe actualizar updated_at????
    {
    $ok = false;
    $conexion = abrir_conexion();
    if ($conexion !== null) {
    try {
    $sql = "UPDATE usuario SET activo=1 WHERE id=:id";
    $sentencia = $conexion->prepare($sql);
    $sentencia->bindParam(":id", $id);
    $ok = $sentencia->execute();

    } catch (PDOException $ex) {
    throw new Exception("error consulta activar_usuario");
    }
    }
    $conexion = null;
    return $ok;
    }
     */
    public function obtener_todos_limite_pagina($limite, $pag)
    {$result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql = "SELECT * FROM usuario  LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name']);

                    }
                    $result['usuarios'] = $usuarios;
                    $result['total_usuarios'] = $this->obtener_numero_usuarios();

                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_todos_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function obtener_bloqueados_limite_pagina($limite, $pag)
    {$result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql = "SELECT * FROM usuario WHERE  activo=0 LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name']);

                    }
                    $result['usuarios'] = $usuarios;
                    $result['total_usuarios'] = $this->obtener_numero_usuarios_estado(0);
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_bloqueados_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function obtener_activos_limite_pagina($limite, $pag)
    {$result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $primero = $limite * ($pag - 1);
                $sql = "SELECT * FROM usuario WHERE activo=1 LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name']);

                    }
                    $result['usuarios'] = $usuarios;
                    $result['total_usuarios'] = $this->obtener_numero_usuarios_estado(1);
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_activos_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function obtener_todos_limite_pagina_like($limite, $pag, $string)
    {$result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $string = "%" . $string . "%";
                $primero = $limite * ($pag - 1);
                $sql = "SELECT * FROM usuario WHERE  username LIKE :string LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->bindParam(":string", $string);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name']);

                    }
                    $result['usuarios'] = $usuarios;
                    $result['total_usuarios'] = $this->obtener_numero_like($string);
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_todos_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }
    public function obtener_actblo_limite_pagina_like($limite, $pag, $string, $activo)
    {$result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $string = "%" . $string . "%";
                $primero = $limite * ($pag - 1);
                $sql = "SELECT * FROM usuario WHERE  activo=:activo AND (username LIKE :string) LIMIT :primero,:limite";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":primero", $primero, PDO::PARAM_INT);
                $sentencia->bindParam(":limite", $limite, PDO::PARAM_INT);
                $sentencia->bindParam(":string", $string);
                $sentencia->bindParam(":activo", $activo);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Usuario($fila['id'], $fila['email'], $fila['username'], $fila['password'], $fila['activo']
                            , $fila['updated_at'], $fila['created_at'], $fila['first_name'], $fila['last_name']);

                    }
                    $result['usuarios'] = $usuarios;
                    $result['total_usuarios'] = $this->obtener_numero_like_estado($activo, $string);
                }

            } catch (PDOException $ex) {
                throw new Exception("error consulta obtener_todos_limite_pagina " . $ex->getMessage());
            }
        }
        $conexion = null;
        return $result;
    }

}

<?php
/*json_decode(json_encode($result[0]), true); */
namespace App\Models;

use App\Models\Usuario;
use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

include_once 'conexion.php';

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
        try {
            $result = DB::select("SELECT COUNT(*) as total FROM usuario WHERE activo=:activo", [":activo" => $estado]);
            return strval($result[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_usuarios_estado");
        }
    }
    public function obtener_numero_like($string)
    {
        try {
            $result = DB::select("SELECT COUNT(*) as total FROM usuario WHERE username LIKE :string", [":string" => $string]);
            return strval($result[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_like");
        }
    }
    public function obtener_numero_like_estado($estado, $string)
    {
        try {
            $result = DB::select("SELECT COUNT(*) as total FROM usuario WHERE activo=:activo AND username LIKE :string",
                [":string" => $string, ":activo" => $estado]);
            return strval($result[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_like_estado");
        }
    }
    public function obtener_numero_usuarios()
    {
        try {
            $result = DB::select("SELECT COUNT(*) as total FROM usuario");
            return strval($result[0]->total);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_numero_like_estado");
        }
    }
    public function insertar_usuario($usuario)
    {
        $result = false;
        try {
            $result = DB::insert("INSERT INTO usuario(email, username, password, activo, updated_at, created_at, first_name,
      last_name)VALUES(:email, :username, :password, 1,NOW() , NOW(), :first_name,:last_name)",
                [":email" => $usuario->getEmail(),
                    ":username" => $usuario->getUsername(),
                    ":password" => $usuario->getPassword(),
                    ":first_name" => $usuario->getFirst_name(),
                    ":last_name" => $usuario->getLast_name()]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta insertar_usuario ");
        }
        return $result;
    }

    public function username_existe($username)
    {
        $existe = false;
        try {
            $result = DB::select("SELECT * FROM usuario WHERE username = :username", [":username" => $username]);
            if (count($result)) {
                $existe = true;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta username_existe");
        }
        return $existe;
    }

    public function actualizar_informacion_usuario($id, $email, $first_name, $last_name)
    {
        $result = false;
        try {
            $result = DB::update("UPDATE usuario SET email=:email, first_name=:first_name, last_name=:last_name, updated_at=NOW() WHERE id=:id",
                [":email" => $email, ":first_name" => $first_name, ":last_name" => $last_name, "id" => $id]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta actualizar_informacion_usuario");
        }
        return (Boolean) $result;
    }
    public function actualizar_password_usuario($id, $password)
    {
        $result = false;
        try {
            $result = DB::update("UPDATE usuario SET password=:password, updated_at=NOW() WHERE id=:id", [":id" => $id, ":password" => $password]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta actualizar_password_usuario");
        }
        return (Boolean) $result;
    }

    public function obtener_usuario_por_id($id)
    {
        try {
            $result = DB::select("SELECT * FROM usuario WHERE id = :id ", ["id" => $id]);
            if (!empty($result)) {
                $r = $result[0];
                $usuario = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo,
                    $r->updated_at, $r->created_at, $r->first_name, $r->last_name);
                return $usuario;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_usuario_por_id");
        }
    }
    public function obtener_usuario_por_username($username)
    {
        try {
            $result = DB::select("SELECT * FROM usuario WHERE username = :username ", [":username" => $username]);
            if (!empty($result)) {
                $r = $result[0];
                $usuario = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo,
                    $r->updated_at, $r->created_at, $r->first_name, $r->last_name);
                return $usuario;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_usuario_por_username");
        }
    }
    public function obtener_usuario_por_username_act_blo($username, $activo)
    {
        try {
            $result = DB::select("SELECT * FROM usuario WHERE username = :username AND activo=:activo", [":username" => $username, ":activo" => $activo]);
            if (!empty($result)) {
                $r = $result[0];
                $usuario = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo,
                    $r->updated_at, $r->created_at, $r->first_name, $r->last_name);
                return $usuario;
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_usuario_por_username_act_blo");
        }
    }
    public function bloquear_activar($id, $accion)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE usuario SET activo=:accion WHERE id=:id", [":accion" => $accion, ":id" => $id]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta bloquear_activar");
        }
        return (Boolean) $ok;
    }

    public function obtener_todos_limite_pagina($limite, $pag)
    {
        $result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        try {
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM usuario  LIMIT :primero,:limite", [":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $usuarios[] = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo, $r->updated_at,
                        $r->created_at, $r->first_name, $r->last_name);
                }
                $result['usuarios'] = $usuarios;
                $result['total_usuarios'] = $this->obtener_numero_usuarios();
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_todos_limite_pagina");
        }
        return $result;
    }
    public function obtener_bloqueados_limite_pagina($limite, $pag)
    {
        $result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        try {
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM usuario WHERE activo=0 LIMIT :primero,:limite", [":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $usuarios[] = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo, $r->updated_at,
                        $r->created_at, $r->first_name, $r->last_name);
                }
                $result['usuarios'] = $usuarios;
                $result['total_usuarios'] = $this->obtener_numero_usuarios_estado(0);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_bloqueados_limite_pagina");
        }
        return $result;
    }
    public function obtener_activos_limite_pagina($limite, $pag)
    {
        $result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        try {
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM usuario WHERE activo=1 LIMIT :primero,:limite", [":primero" => $primero, ":limite" => $limite]);
            if (count($re)) {
                foreach ($re as $r) {
                    $usuarios[] = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo, $r->updated_at,
                        $r->created_at, $r->first_name, $r->last_name);
                }
                $result['usuarios'] = $usuarios;
                $result['total_usuarios'] = $this->obtener_numero_usuarios_estado(1);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_activos_limite_pagina");
        }
        return $result;
    }
    public function obtener_todos_limite_pagina_like($limite, $pag, $string)
    {
        $result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        try {
            $string = "%" . $string . "%";
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM usuario WHERE  username LIKE :string LIMIT :primero,:limite", [":primero" => $primero, ":limite" => $limite, ":string" => $string]);
            if (count($re)) {
                foreach ($re as $r) {
                    $usuarios[] = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo, $r->updated_at,
                        $r->created_at, $r->first_name, $r->last_name);
                }
                $result['usuarios'] = $usuarios;
                $result['total_usuarios'] = $this->obtener_numero_like($string);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_todos_limite_pagina_like");
        }
        return $result;
    }

    public function obtener_actblo_limite_pagina_like($limite, $pag, $string, $activo)
    {
        $result = array();
        $result['usuarios'] = array();
        $result['total_usuarios'] = 0;
        $usuarios = array();
        try {
            $string = "%" . $string . "%";
            $primero = $limite * ($pag - 1);
            $re = DB::select("SELECT * FROM usuario WHERE  activo=:activo AND (username LIKE :string) LIMIT :primero,:limite",
                [":primero" => $primero, ":limite" => $limite, ":string" => $string, ":activo" => $activo]);
            if (count($re)) {
                foreach ($re as $r) {
                    $usuarios[] = new Usuario($r->id, $r->email, $r->username, $r->password, $r->activo, $r->updated_at,
                        $r->created_at, $r->first_name, $r->last_name);
                }
                $result['usuarios'] = $usuarios;
                $result['total_usuarios'] = $this->obtener_numero_like_estado($activo, $string);
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error consulta obtener_actblo_limite_pagina_like");
        }
        return $result;
    }
}

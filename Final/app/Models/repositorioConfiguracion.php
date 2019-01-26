<?php
namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class RepositorioConfiguracion
{
    public function setTitulo($titulo)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE configuracion SET valor=:valor WHERE variable='titulo'", [':valor' => $titulo]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error setTitulo");
        }
        return (Boolean) $ok;
    }
    public function setDescripcion($descripcion)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE configuracion SET valor=:valor WHERE variable='descripcion'", [':valor' => $descripcion]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error setDescripcion");
        }
        return (Boolean) $ok;
    }
    public function setEmail($email)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE configuracion SET valor=:valor WHERE variable='email'", [':valor' => $email]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error setEmail");
        }
        return (Boolean) $ok;
    }

    public function setLimite($limite)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE configuracion SET valor=:valor WHERE variable='limite'", [':valor' => $limite]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error setDLimite");
        }
        return (Boolean) $ok;
    }

    public function habilitacion($valor)
    {
        $ok = false;
        try {
            $ok = DB::update("UPDATE configuracion SET valor=:valor WHERE variable='habilitado'", [':valor' => $valor]);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error habilitacion");
        }
        return (Boolean) $ok;
    }
    public function getTitulo()
    {
        try {
            $re = DB::select("SELECT valor FROM configuracion WHERE variable='titulo'");
            return strval($re[0]->valor);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error getTitulo");
        }
    }
    public function getDescripcion()
    {
        try {
            $re = DB::select("SELECT valor FROM configuracion WHERE variable='descripcion'");
            return strval($re[0]->valor);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error getDescripcion");
        }
    }

    public function getEmail()
    {
        try {
            $re = DB::select("SELECT valor FROM configuracion WHERE variable='email'");
            return strval($re[0]->valor);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error getEmail");
        }
    }

    public function getLimite()
    {
        try {
            $re = DB::select("SELECT valor FROM configuracion WHERE variable='limite'");
            return strval($re[0]->valor);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error getLimite");
        }
    }
    public function getHabilitado()
    {
        try {
            $re = DB::select("SELECT valor FROM configuracion WHERE variable='habilitado'");
            return strval($re[0]->valor);
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error getHabilitado");
        }
    }
    public function obtener_configuracion()
    {
        $arreglo = array();
        try {
            $re = DB::select("SELECT variable,valor from configuracion");
            if (count($re)) {
                foreach ($re as $r) {
                    $arreglo[$r->variable] = $r->valor;
                }
            }
        } catch (\Illuminate\Database\QueryException | PDOException $e) {
            throw new Exception("error botener_configuracion");
        }
        return $arreglo;
    }

}

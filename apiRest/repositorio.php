<?php
include_once "conexion.php";
class Repositorio
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function todos()
    {
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT i.id,i.nombre,i.director,i.direccion,i.telefono,r.id as region_sanitaria_id,r.nombre AS region_sanitaria_nombre,i.tipo_institucion_id,t.nombre AS tipo_institucion_nombre
                    FROM institucion i INNER JOIN localidad l ON(i.localidad_id=l.id)
                                       INNER JOIN partido p ON (l.partido_id=p.id)
                                       INNER JOIN region_sanitaria r ON (p.region_sanitaria_id=r.id)
                                       INNER JOIN tipo_institucion t ON(i.tipo_institucion_id=t.id)";
                $sentencia = $conexion->query($sql);
                return $sentencia->fetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $ex) {}

        }
    }
    public function institucion_id($id)
    {
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT i.id,i.nombre,i.director,i.direccion,i.telefono,r.id as region_sanitaria_id,r.nombre AS region_sanitaria_nombre,i.tipo_institucion_id,t.nombre AS tipo_institucion_nombre
                FROM institucion i INNER JOIN localidad l ON(i.localidad_id=l.id)
                                       INNER JOIN partido p ON (l.partido_id=p.id)
                                       INNER JOIN region_sanitaria r ON (p.region_sanitaria_id=r.id)
                                   INNER JOIN tipo_institucion t ON(i.tipo_institucion_id=t.id)
                                   WHERE i.id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                return $sentencia->Fetch(PDO::FETCH_OBJ);
            } catch (PDOException $ex) {

            }
        }
    }
    public function region_id($id)
    {
        $conexion = abrir_conexion();
        if ($conexion !== null) {
            try {
                $sql = "SELECT i.id,i.nombre,i.director,i.direccion,i.telefono,r.id as region_sanitaria_id,r.nombre AS region_sanitaria_nombre,i.tipo_institucion_id,t.nombre AS tipo_institucion_nombre
                FROM institucion i INNER JOIN localidad l ON(i.localidad_id=l.id)
                                       INNER JOIN partido p ON (l.partido_id=p.id)
                                       INNER JOIN region_sanitaria r ON (p.region_sanitaria_id=r.id)
                                   INNER JOIN tipo_institucion t ON(i.tipo_institucion_id=t.id)
                                   WHERE r.id=:id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(":id", $id);
                $sentencia->execute();
                return $sentencia->FetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $ex) {

            }
        }
    }
}

<?php

function abrir_conexion()
{    $NOMBRE_SERVIDOR="localhost";
     $NOMBRE_USUARIO="grupo12";
     $PASSWORD="MjM1ZDVlMzBhNWNl";
     $NOMBRE_BD="grupo12";
    $conexion=null;
    try {
        $conexion = new PDO('mysql:host='.$NOMBRE_SERVIDOR.'; dbname='.$NOMBRE_BD,$NOMBRE_USUARIO,$PASSWORD);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->exec("SET CHARACTER SET utf8");
    } catch (PDOException $ex) {
    }

    return $conexion;

}

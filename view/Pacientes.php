<?php

class Pacientes extends TwigView {

  public function show($datos) {
    $datos["permisos"] = array('index', 'new', 'update', 'show', 'destroy' );
    echo self::getTwig()->render('pacientes.twig', $datos);
  }

  public function cargarPagina($datos){
    $datos["permisos"] = array('index', 'new', 'update', 'show', 'destroy' );
    $contenido = self::getTwig()->render('cuerpoTablaPacientes.twig', $datos);
    $respuesta = array('estado' => "si hay",'contenido' => $contenido);

    self::jsonEncode($respuesta);
  }
}

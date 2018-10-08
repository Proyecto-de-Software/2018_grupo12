<?php

class Pacientes extends TwigView {

  public function show($pacientes) {
    $permisos = array('index', 'new', 'update', 'show', 'destroy' );
    echo self::getTwig()->render('pacientes.twig', array('pacientes'=> $pacientes, 'permisos' => $permisos ));
  }

  public function cargarPagina($pacientes){
    $contenido = self::getTwig()->render('cuerpoTablaPacientes.twig',array('pacientes' => $pacientes));
    $datos = array('estado' => "si hay",'contenido' => $contenido);

    self::jsonEncode($datos);
  }
}

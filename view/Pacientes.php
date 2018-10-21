<?php

class Pacientes extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('pacientes.twig', $datos);
  }

  public function cargarPagina($datos){
    $contenido = self::getTwig()->render('cuerpoTablaPacientes.twig', $datos);
    $respuesta = array('estado' => "si hay",'contenido' => $contenido);

    self::jsonEncode($respuesta);
  }

  public function detallePaciente($paciente){
    $respuesta["contenido"] = self::getTwig()->render('detallePaciente.twig',$paciente);
    $respuesta["estado"] = "success";

    self::jsonEncode($respuesta);
  }
}

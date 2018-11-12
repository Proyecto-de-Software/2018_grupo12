<?php

class Consultas extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('moduloConsulta/consultas.twig', $datos);
  }

  public function cargarPagina($datos,$cantPaginasRestantes){
    $contenido = self::getTwig()->render('moduloConsulta/cuerpoTablaConsultas.twig', $datos);
    $respuesta = array('estado' => "si hay",'pagRestantes' => $cantPaginasRestantes,'contenido' => $contenido);

    self::jsonEncode($respuesta);
  }

  public function detalleConsulta($consulta){
    $respuesta["contenido"] = self::getTwig()->render('moduloConsulta/detalleConsulta.twig',$consulta);
    $respuesta["estado"] = "success";

    self::jsonEncode($respuesta);
  }
}

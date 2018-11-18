<?php

class Reportes extends TwigView {

    public function show($datos) {
      echo self::getTwig()->render('reportes.twig',$datos);
    }

    public function cargarPagina($datos,$cantPaginasRestantes){
      $contenido = self::getTwig()->render('moduloReporte/cuerpoTablaConsultas.twig', $datos);
      $respuesta = array('estado' => "si hay",'pagRestantes' => $cantPaginasRestantes,'contenido' => $contenido);
      self::jsonEncode($respuesta);
    }
}

<?php

class Roles extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('roles.twig', $datos);
  }

  public function cargarPagina($datos,$cantPaginasRestantes){
    $contenido = self::getTwig()->render('moduloRol/cuerpoTablaRoles.twig', $datos);
    $respuesta = array('estado' => "si hay",'pagRestantes' => $cantPaginasRestantes,'contenido' => $contenido);

    self::jsonEncode($respuesta);
  }
}

<?php

class Configuracion extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('configuracion.twig', $datos);
  }
}

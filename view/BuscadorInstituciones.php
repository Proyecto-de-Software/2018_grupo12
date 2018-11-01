<?php

class BuscadorInstituciones extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('buscadorInstituciones.twig', $datos);
  }

}

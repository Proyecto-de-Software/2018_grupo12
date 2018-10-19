<?php

class PaginaMantenimiento extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('paginaMantenimiento.twig', $datos);
  }

}

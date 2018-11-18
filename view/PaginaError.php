<?php

class PaginaError extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('paginaError.twig',$datos);
  }

}

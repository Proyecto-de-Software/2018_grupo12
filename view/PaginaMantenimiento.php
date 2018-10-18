<?php

class PaginaMantenimiento extends TwigView {

  public function show() {
    echo self::getTwig()->render('paginaMantenimiento.twig');
  }

}

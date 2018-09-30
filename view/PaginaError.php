<?php

class PaginaError extends TwigView {

  public function show() {
    echo self::getTwig()->render('paginaError.twig');
  }

}

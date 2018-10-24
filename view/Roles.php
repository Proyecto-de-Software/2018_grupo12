<?php

class Roles extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('roles.twig', $datos);
  }
}

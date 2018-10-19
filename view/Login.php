<?php

class Login extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('login.twig', $datos);
  }

}

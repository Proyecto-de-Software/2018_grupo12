<?php

class Configuracion extends TwigView {

  public function show($config, $error = null, $success = null) {
    $config["error"] = $error;
    $config["success"] = $success;
    echo self::getTwig()->render('configuracion.twig', $config);
  }
}

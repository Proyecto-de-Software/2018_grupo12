<?php

class Home extends TwigView {

  public function show($modulos) {
    echo self::getTwig()->render('home.twig', $modulos);
  }

}

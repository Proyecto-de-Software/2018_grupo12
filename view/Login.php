<?php

class Login extends TwigView {
  
  public function show() {
    echo self::getTwig()->render('login.twig');
  }

}

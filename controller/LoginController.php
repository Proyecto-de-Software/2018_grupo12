<?php

class LoginController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function mostrarLogin(){
    $view = new Login();
    $view->show();
  }

  public function redirectHome(){
    $view = new Home();
    $view->show();
  }

}

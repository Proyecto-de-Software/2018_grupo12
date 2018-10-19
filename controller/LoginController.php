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
    $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
    $view = new Login();
    $view->show($datos);
  }

  public function redirectHome(){
    $view = new Home();
    $repoPermisos = RepositorioPermiso::getInstance();
    $id = $_SESSION["id"];

    $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
    $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
    $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
    $datos["username"] = $_SESSION["userName"];
    $view->show($datos);
  }

}

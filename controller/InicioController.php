<?php

class InicioController {

  private static $instance;

  public static function getInstance() {

    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function mostrarInicio(){
    if (! Validador::getInstance()->pagina_habilitada()){
      Validador::getInstance()->mostrarPaginaMantenimiento();
    }elseif (Validador::getInstance()->sesion_iniciada()){
      LoginController::getInstance()->redirectHome();
    }else{
      $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
      $view = new Inicio();
      $view->show($datos);
    }
  }

}

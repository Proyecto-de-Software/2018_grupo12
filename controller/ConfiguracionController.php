<?php

class ConfiguracionController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
        self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function redirectConfiguracion(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $view = new Configuracion();
      $view->show($config->obtener_configuracion());
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function guardarConfiguracion(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $view = new Configuracion();

      //intentan hacer un envio vacio desde url por ende redirecciono
      if (! (isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["email"]) && isset($_POST["limite"]) && isset($_POST["habilitado"]))) {
        InicioController::getInstance()->mostrarInicio();
      }else {
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $email = $_POST["email"];
        $limite = $_POST["limite"];
        $habilitado = $_POST["habilitado"];

        //Valido que el email sea correcto y que los campos no esten vacios
        //Valido que el select "habilitado" tenga los valores correctos
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $view->show($config->obtener_configuracion(),"Email incorrecto");
        }elseif(!($titulo && $descripcion && $email && $limite && ($habilitado == "0" || $habilitado == "1"))) {
          $view->show($config->obtener_configuracion(),"Los datos ingresados son incorrectos");
        }else {
          //Guardo la configuracion nueva
          $config->setTitulo($titulo);
          $config->setDescripcion($descripcion);
          $config->setEmail($email);
          $config->setLimite($limite);

          if ($habilitado == 1) {
            $config->habilitar();
          }else {
            $config->deshabilitar();
          }

          $view->show($config->obtener_configuracion(), null, "Los datos se guardaron correctamente");
        }
      }
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }
}

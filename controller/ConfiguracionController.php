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
      $repoPermisos = RepositorioPermiso::getInstance();
      $id = $_SESSION["id"];

      $datos = $config->obtener_configuracion();
      $datos["tituloPag"] = $config->getTitulo();
      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"configuracion");

      $view = new Configuracion();
      $view->show($datos);
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function guardarConfiguracion(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $view = new Configuracion();

      //intentan hacer un envio vacio por ende redirecciono
      if (! (isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["email"]) && isset($_POST["limite"]) && isset($_POST["habilitado"]))) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "Complete todos los campos"));
      }else {
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $email = $_POST["email"];
        $limite = $_POST["limite"];
        $habilitado = $_POST["habilitado"];

        //Valido que el email sea correcto y que los campos no esten vacios
        //Valido que el select "habilitado" tenga los valores correctos
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "El email ingresado es incorrecto"));
        }elseif(!($titulo && $descripcion && $email && $limite && ($habilitado == "0" || $habilitado == "1"))) {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "Los datos ingresados son incorrectos"));;
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

          TwigView::jsonEncode(array('estado' => "success", 'mensaje'=> "Los datos se guardaron correctamente"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }
}

<?php

class BuscadorInstitucionesController {

  private static $instance;

  public static function getInstance() {

    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function mostrarBuscadorInstituciones(){
    $view = new BuscadorInstituciones();
    $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
    $datos["descripcion"] = RepositorioConfiguracion::getInstance()->getDescripcion();

    if (Validador::getInstance()->sesion_iniciada()) {
      $repoPermisos = RepositorioPermiso::getInstance();
      $id = $_SESSION["id"];

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["pagina"] = 'layoutLogueado.twig';
    }else {
      $datos["pagina"] = 'inicio.twig';
    }
    $view->show($datos);
  }

}

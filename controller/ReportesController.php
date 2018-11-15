<?php

class ReportesController {

  private static $instance;

  public static function getInstance() {

    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function redirectReportes(){
    $repoPermisos = RepositorioPermiso::getInstance();
    $repoConsulta = RepositorioConsulta::getInstance();

    $id = $_SESSION["id"];

    $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
    $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
    $datos["username"] = $_SESSION["userName"];
    $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"reporte");
    $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
    $datos["porcentajesMotivo"] = $repoConsulta->motivo_porcentaje();
    $datos["porcentajesGenero"] = $repoConsulta->genero_porcentaje();
    $datos["porcentajesLocalidad"] = $repoConsulta->localidad_porcentaje();

    $view = new Reportes();
    $view->show($datos);
  }

}

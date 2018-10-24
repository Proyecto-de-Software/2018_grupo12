<?php

class RolController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
        self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function redirectRoles(){
    try {
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Roles();

      $id = $_SESSION["id"];

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"rol");
      $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();

      $view->show($datos);
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function cargarPagina(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $repoRol = RepositorioRol::getInstance();
      $view = new Roles();

      $pagina = $_POST["pagina"];
      $nombre = strtolower($_POST["nombre"]);
      $limite = $config->getLimite();
      $id = $_SESSION["id"];

      if ($nombre) {
        $roles = $repoRol->obtener_por_nombre($nombre, $limite, $pagina);
      }else {
        $pacientes = $repoPaciente->obtener_por_nombre_y_apellido($nombre,$apellido,$limite,$pagina);
      }

      if (empty($pacientes)) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{
        foreach ($pacientes as $paciente) {
          $idObraSocial = $paciente->getObra_social_id();
          $idTipoDoc = $paciente->getTipo_doc_id();

          $obraSocialPaciente = $repoObraSocial->obtener_por_id($idObraSocial);
          if ($obraSocialPaciente) {
            $paciente->setNombreObraSocial($obraSocialPaciente->getNombre());
          }

          $tipoDocPaciente = $repoTipoDoc->obtener_por_id($idTipoDoc);
          if ($tipoDocPaciente) {
            $paciente->setNombreTipoDocumento($tipoDocPaciente->getNombre());
          }
        }

        $datos["pacientes"] = $pacientes;
        $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"paciente");

        $view->cargarPagina($datos);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

}

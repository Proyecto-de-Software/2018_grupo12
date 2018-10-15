<?php

class PacientesController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
        self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function redirectPacientes(){
    try {
      $repoPaciente = RepositorioPaciente::getInstance();
      $repoObraSocial = RepositorioObraSocial::getInstance();
      $repoTipoDoc = RepositorioTipoDocumento::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Pacientes();

      $id = $_SESSION["id"];
      $limite = RepositorioConfiguracion::getInstance()->getLimite();
      $pacientes = $repoPaciente->obtener_todos_limite_pagina($limite,1);

      foreach ($pacientes as $paciente) {
        $idObraSocial = $paciente->getObra_social_id();
        $idTipoDoc = $paciente->getTipo_doc_id();

        $paciente->setNombreObraSocial(($repoObraSocial->obtener_por_id($idObraSocial))->getNombre());
        $paciente->setNombreTipoDocumento(($repoTipoDoc->obtener_por_id($idTipoDoc))->getNombre());
      }

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"paciente");
      $datos["pacientes"] = $pacientes;
      $datos["tiposDocumentos"] = $repoTipoDoc->obtener_todos();

      $view->show($datos);
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function cargarPagina(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $repoPaciente = RepositorioPaciente::getInstance();
      $repoObraSocial = RepositorioObraSocial::getInstance();
      $repoTipoDoc = RepositorioTipoDocumento::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Pacientes();

      $pagina = $_POST["pagina"];
      $tipoBusqueda = $_POST["tipoBusqueda"];
      $nombre = strtolower($_POST["nombre"]);
      $apellido = strtolower($_POST["apellido"]);
      $tipoDoc = $_POST["tipoDoc"];
      $nroDoc = $_POST["nroDoc"];
      $nroHistoriaClinica = $_POST["nroHistoriaClinica"];
      $limite = $config->getLimite();
      $id = $_SESSION["id"];

      //Identifico tipo de busqueda
      switch ($tipoBusqueda) {
        case 'nombre_y_apellido':
          if ($nombre &&  !$apellido) {
            $pacientes = $repoPaciente->obtener_por_nombre($nombre, $limite, $pagina);
          }elseif (!$nombre && $apellido) {
            $pacientes = $repoPaciente->obtener_por_apellido($apellido, $limite, $pagina);
          }else {
            $pacientes = $repoPaciente->obtener_por_nombre_y_apellido($nombre,$apellido,$limite,$pagina);
          }
          break;
        case 'dni':
          $pacientes = array();
          $paciente = $repoPaciente->obtener_por_datos_doc($tipoDoc, $nroDoc);
          if ($paciente) {
            $pacientes[] = $paciente;
          }
          break;
        case 'historia_clinica':
          $pacientes = array();
          $paciente = $repoPaciente->obtener_por_nro_historia_clinica($nroHistoriaClinica);
          if ($paciente) {
            $pacientes[] = $paciente;
          }
          break;
        default:
          $pacientes = $repoPaciente->obtener_todos_limite_pagina($limite,$pagina);
          break;
      }

      if (empty($pacientes)) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{
        foreach ($pacientes as $paciente) {
          $idObraSocial = $paciente->getObra_social_id();
          $idTipoDoc = $paciente->getTipo_doc_id();

          $paciente->setNombreObraSocial(($repoObraSocial->obtener_por_id($idObraSocial))->getNombre());
          $paciente->setNombreTipoDocumento(($repoTipoDoc->obtener_por_id($idTipoDoc))->getNombre());
        }

        $datos["pacientes"] = $pacientes;
        $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"paciente");

        $view->cargarPagina($datos);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function eliminarPaciente(){
    try {
      $repoPaciente = RepositorioPaciente::getInstance();

      $id = $_POST["id"];

      if ($repoPaciente->eliminar_paciente($id)) {
        TwigView::jsonEncode(array('estado' => "success", 'mensaje'=> "Paciente eliminado correctamente"));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }

  public function detallePaciente(){
    try {
      $repoPaciente = RepositorioPaciente::getInstance();
      $view = new Pacientes();

      $id = $_POST["id"];

      $view->detallePaciente($repoPaciente->obtener_por_id_info_completa($id));
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }
}

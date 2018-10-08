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
      $view = new Pacientes();

      $limite = RepositorioConfiguracion::getInstance()->getLimite();
      $pacientes = $repoPaciente->obtener_todos_limite_pagina($limite,1);

      foreach ($pacientes as $paciente) {
        $idObraSocial = $paciente->getObra_social_id();
        $idTipoDoc = $paciente->getTipo_doc_id();

        $paciente->setNombreObraSocial(($repoObraSocial->obtener_por_id($idObraSocial))->getNombre());
        $paciente->setNombreTipoDocumento(($repoTipoDoc->obtener_por_id($idTipoDoc))->getNombre());
      }

      $datos = array('tiposDocumentos' => $repoTipoDoc->obtener_todos(),
                     'pacientes' => $pacientes);
      $view->show($datos);
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function cargarPagina(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
        $config = RepositorioConfiguracion::getInstance();
        $repoPaciente = RepositorioPaciente::getInstance();
        $repoObraSocial = RepositorioObraSocial::getInstance();
        $repoTipoDoc = RepositorioTipoDocumento::getInstance();
        $view = new Pacientes();

        $pagina = $_POST["pagina"];
        $tipoBusqueda = $_POST["tipoBusqueda"];
        $nombre = strtolower($_POST["nombre"]);
        $apellido = strtolower($_POST["apellido"]);
        $tipoDoc = $_POST["tipoDoc"];
        $nroDoc = $_POST["nroDoc"];
        $nroHistoriaClinica = $_POST["nroHistoriaClinica"];
        $limite = $config->getLimite();

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
          $datos = array('pacientes' => $pacientes);
          $view->cargarPagina($datos);
        }
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }
}

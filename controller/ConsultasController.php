<?php

class ConsultasController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
        self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function redirectConsultas(){
    try {
      $repoConsultas = RepositorioConsulta::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $repoMotivos = RepositorioMotivo::getInstance();
      $repoAcompanamientos = RepositorioAcompanamiento::getInstance();
      $repoTratamientos = RepositorioTratamiento::getInstance();
      $view = new Consultas();

      $id = $_SESSION["id"];
      $limite = RepositorioConfiguracion::getInstance()->getLimite();

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"consulta");
      $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
      $datos["motivos"] = $repoMotivos->obtener_todos();
      $datos["acompanamientos"] = $repoAcompanamientos->obtener_todos();
      $datos["tratamientos"] = $repoTratamientos->obtener_todos();

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
      $repoConsultas = RepositorioConsulta::getInstance();
      $view = new Consultas();

      $pagina = $_POST["pagina"];
      $tipoBusqueda = $_POST["tipoBusqueda"];
      $tipoDoc = $_POST["tipoDoc"];
      $nroDoc = $_POST["nroDoc"];
      $nroHistoriaClinica = $_POST["nroHistoriaClinica"];
      $limite = $config->getLimite();
      $id = $_SESSION["id"];

      //Identifico tipo de busqueda
      switch ($tipoBusqueda) {
        case 'dni':
          if (! $tipoDoc) {
            $resultado = $repoConsultas->obtener_numero_limite_pagina($nroDoc,$limite,$pagina);
          }else {
            $resultado = $repoConsultas->obtener_documento_limite_pagina($tipoDoc,$nroDoc,$limite,$pagina);
          }
          break;
        case 'historia_clinica':
          $resultado = $repoConsultas->obtener_historia_limite_pagina($nroHistoriaClinica,$limite,$pagina);
          break;
        default:
          $resultado = $repoConsultas->obtener_todos_limite_pagina($limite,$pagina);
          break;
      }

      if (empty($resultado["consultas"])) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{

        $datos["consultas"] = $resultado["consultas"];
        $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"consulta");

        $cantPaginasRestantes = (ceil( $resultado["total_consultas"] / $limite)) - $pagina;
        $view->cargarPagina($datos,$cantPaginasRestantes);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function cargarPaginaPacientesParaConsulta(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $repoPaciente = RepositorioPaciente::getInstance();
      $repoObraSocial = RepositorioObraSocial::getInstance();
      $repoTipoDoc = RepositorioTipoDocumento::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Consultas();

      $pagina = $_POST["pagina"];
      $tipoBusqueda = $_POST["tipoBusqueda"];
      $tipoDoc = $_POST["tipoDoc"];
      $nroDoc = $_POST["nroDoc"];
      $nroHistoriaClinica = $_POST["nroHistoriaClinica"];
      $limite = $config->getLimite();
      $id = $_SESSION["id"];

      //Identifico tipo de busqueda
      switch ($tipoBusqueda) {
        case 'dni':
          if (! $tipoDoc) {
            $resultado = $repoPaciente->obtener_por_num_doc($nroDoc,$limite,$pagina);
          }else {
            $resultado = $repoPaciente->obtener_por_datos_doc($tipoDoc,$nroDoc,$limite,$pagina);
          }
          break;
        case 'historia_clinica':
          $resultado = $repoPaciente->obtener_por_nro_historia_clinica($nroHistoriaClinica,$limite,$pagina);
          break;
        default:
          $resultado = $repoPaciente->obtener_todos_limite_pagina($limite,$pagina);
          break;
      }

      if (empty($resultado["pacientes"])) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{
        foreach ($resultado["pacientes"] as $paciente) {
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

        $datos["pacientes"] = $resultado["pacientes"];
        $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"paciente");

        $cantPaginasRestantes = (ceil( $resultado["total_pacientes"] / $limite)) - $pagina;
        $view->cargarPaginaPacientesParaConsulta($datos,$cantPaginasRestantes);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function obtenerCoordenadasDerivaciones(){
    $repoPaciente = RepositorioPaciente::getInstance();

    $id = $_POST["id"];

    $derivaciones = $repoPaciente->coordenadas_derivaciones($id);

    TwigView::jsonEncode($derivaciones);
  }

  public function eliminarConsulta(){
    try {
      $repoConsulta = RepositorioConsulta::getInstance();

      $id = $_POST["id"];

      if ($repoConsulta->eliminar_consulta($id)) {
        TwigView::jsonEncode(array('estado' => "success", 'mensaje'=> "Cnonsulta eliminada correctamente"));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }

  public function detalleConsulta(){
    try {
      $repoConsulta = RepositorioConsulta::getInstance();
      $view = new Consultas();

      $id = $_POST["id"];

      $view->detalleConsulta($repoConsulta->obtener_info_completa($id));
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }

  public function agregarConsulta(){
    try {
      $idPaciente = (isset($_POST["id"])) ? $_POST["id"] : null;
      $fecha = (isset($_POST["fecha"])) ? $_POST["fecha"] : null;
      $motivo = (isset($_POST["motivo"])) ? $_POST["motivo"] : null;
      $derivacion = (isset($_POST["derivacion"])) ? $_POST["derivacion"] : null;
      $internacion = (isset($_POST["internacion"])) ? $_POST["internacion"] : null;
      $tratamiento = (isset($_POST["tratamiento"])) ? $_POST["tratamiento"] : null;
      $acompanamiento = (isset($_POST["acompanamiento"])) ? $_POST["acompanamiento"] : null;
      $articulacion = (isset($_POST["articulacion"])) ? $_POST["articulacion"] : null;
      $diagnostico = (isset($_POST["diagnostico"])) ? $_POST["diagnostico"] : null;
      $observaciones = (isset($_POST["observaciones"])) ? $_POST["observaciones"] : null;

      $date = date('Y-m-d',time());

      $fechaIngresada = strtotime($fecha);
      $date = strtotime($date);

      if (! $idPaciente) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Seleccione un paciente de la lista"));
        return false;
      }elseif (!($fecha && $motivo && $diagnostico && ($internacion == 1 || $internacion == 0))) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete los campos obligatorios"));
        return false;
      }elseif ($fechaIngresada > $date) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "La fecha tiene que ser menor o igual a la actual"));
        return false;
      }elseif (strlen($articulacion) > 255 || strlen($diagnostico) > 255 || strlen($observaciones) > 255) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Los textos ingresados deben tener un maximo de 255 caracteres"));
        return false;
      }

      $repoConsulta = RepositorioConsulta::getInstance();

      if ($repoConsulta->insertar_consulta($idPaciente, $fecha, $motivo, $derivacion, $articulacion,
          (int)$internacion, $diagnostico, $observaciones, $tratamiento, $acompanamiento)){
        TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Consulta guardada correctamente"));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

  public function formularioModificacionConsulta(){
  try {
        $id = $_POST["id"];

        $repoConsulta = RepositorioConsulta::getInstance();

        $consulta = $repoConsulta->obtener_info_completa($id);

        $datos["estado"] = "success";
        $datos["tratamiento"] = $consulta["tratamiento_farmacologico_id"];
        $datos["articulacion"] = $consulta["articulacion_con_instituciones"];
        $datos["diagnostico"] = $consulta["diagnostico"];
        $datos["observaciones"] = $consulta["observaciones"];

        TwigView::jsonEncode($datos);
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function modificarConsulta(){
    try {
      $id = (isset($_POST["id"])) ? $_POST["id"] : null;
      $tratamiento = (isset($_POST["tratamiento"])) ? $_POST["tratamiento"] : null;
      $articulacion = (isset($_POST["articulacion"])) ? $_POST["articulacion"] : null;
      $diagnostico = (isset($_POST["diagnostico"])) ? $_POST["diagnostico"] : null;
      $observaciones = (isset($_POST["observaciones"])) ? $_POST["observaciones"] : null;

      $repoConsulta = RepositorioConsulta::getInstance();

      if (! $id) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "error"));
        return false;
      }elseif (! $diagnostico) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete los campos obligatorios"));
        return false;
      }elseif (strlen($articulacion) > 255 || strlen($diagnostico) > 255 || strlen($observaciones) > 255) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Los textos ingresados deben tener un maximo de 255 caracteres"));
        return false;
      }

      $result = $repoConsulta->actualizar_consulta($id,$tratamiento,$articulacion,$diagnostico,$observaciones);


      if ($result){
        TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Consulta guardada correctamente"));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }
}

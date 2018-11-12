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
      $view = new Consultas();

      $id = $_SESSION["id"];
      $limite = RepositorioConfiguracion::getInstance()->getLimite();
      //$resultado = $repoConsultas->obtener_todos_limite_pagina($limite,1);
      //$consultas = $resultado["consultas"];

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"consulta");
      //$datos["consultas"] = $consultas;
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
            $resultado = $repoConsultas->obtener_por_num_doc($nroDoc,$limite,$pagina);
          }else {
            $resultado = $repoConsultas->obtener_por_datos_doc($tipoDoc,$nroDoc,$limite,$pagina);
          }
          break;
        case 'historia_clinica':
          $resultado = $repoConsultas->obtener_por_nro_historia_clinica($nroHistoriaClinica,$limite,$pagina);
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

  public function validarPacienteCompleto($nombre, $apellido,$lNacimiento,$fNacimiento,$partido,$localidad,
  $domicilio,$genero,$tieneDoc,$tipoDoc,$nroDoc,$nroHC,$nroCarpeta,$nroTel_cel,$obraSocial,$regionSanitaria,$id=""){

    $repoPaciente = RepositorioPaciente::getInstance();

    $date = date('Y-m-d',time());

    $fechaIngresada = strtotime($fNacimiento);
    $date = strtotime($date);

    if (!($nombre && $apellido && $fNacimiento && $domicilio && ($tieneDoc == 1 || $tieneDoc == 0) && $tipoDoc && $nroDoc)) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete los campos obligatorios"));
      return false;
    }elseif (strlen($nroDoc) > 10 || (int)$nroDoc < 10000000) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El numero de documento debe tener entre 8 y 10 caracteres"));
      return false;
    }elseif(!preg_match("/^[a-zA-Z ]+$/",$nombre) || !preg_match("/^[a-zA-Z ]+$/",$apellido)){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Nombre y apellido deben contener solo letras"));
      return false;
    }elseif($nroHC && ((int)$nroHC < 0 || (int)$nroHC > 999999)){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Numero de historia tiene un maximo de 6 numeros y es positivo"));
      return false;
    }elseif ($nroCarpeta && ((int)$nroCarpeta > 99999 || (int)$nroCarpeta < 0)) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Numero de carpeta tiene un maximo de 5 numeros y es positivo"));
      return false;
    }elseif ($fechaIngresada > $date) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "La fecha tiene que ser menor a la actual"));
      return false;
    }elseif($nroTel_cel && !preg_match("/^[0-9 +]+$/",$nroTel_cel)){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "numero de telefono/celular incorrecto"));
      return false;
    }elseif (($pac = $repoPaciente->existe_doc($tipoDoc, $nroDoc)) && ((!$id) || $pac->getId() != $id)) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El numero de documento ya existe"));
      return false;
    }elseif (($nroHC && $pac = $repoPaciente->existe_historia_clinica($nroHC)) && (!$id || $pac->getId() != $id)){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El numero de historia clinica ya existe"));
      return false;
    }

    return true;
  }

  public function agregarPacienteCompleto(){
    try {
      $nombre = strtolower($_POST["nombre"]);
      $apellido = strtolower($_POST["apellido"]);
      $lNacimiento = strtolower($_POST["lNacimiento"]);
      $fNacimiento = $_POST["fNacimiento"];
      $partido = $_POST["partido"];
      $localidad = $_POST["localidad"];
      $domicilio = $_POST["domicilio"];
      $genero = $_POST["genero"];
      $tieneDoc = $_POST["tieneDoc"];
      $tipoDoc = $_POST["tipoDoc"];
      $nroDoc = $_POST["nroDoc"];
      $nroHC = $_POST["nroHC"];
      $nroCarpeta = $_POST["nroCarpeta"];
      $nroTel_cel = $_POST["nroTel_cel"];
      $obraSocial = $_POST["obraSocial"];
      $regionSanitaria = $_POST["regionSanitaria"];

      $repoPaciente = RepositorioPaciente::getInstance();

      //Valido que los campos no esten vacios y sean correctos
      if($this->validarPacienteCompleto($nombre, $apellido,$lNacimiento,$fNacimiento,$partido,
      $localidad,$domicilio,$genero,$tieneDoc,$tipoDoc,$nroDoc,$nroHC,$nroCarpeta,$nroTel_cel,$obraSocial,$regionSanitaria)){

        $paciente = new Paciente('',$apellido,$nombre,$fNacimiento,$lNacimiento,$localidad,$partido,$regionSanitaria,
                    $domicilio,$genero,$tieneDoc,$tipoDoc,$nroDoc,$nroTel_cel,$nroHC,$nroCarpeta,$obraSocial,0);

        if ($repoPaciente->insertar_paciente($paciente)){
          TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Paciente guardado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

  public function formularioModificacionPaciente(){
  try {
        $id = $_POST["id"];

        $repoPaciente = RepositorioPaciente::getInstance();

        $paciente = $repoPaciente->obtener_por_id_info_completa($id);

        if ($paciente["borrado"]) {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "el paciente no existe o fue borrado"));
        }else {
          $paciente["estado"] = "success";
          TwigView::jsonEncode($paciente);
        }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function modificarPaciente(){
    try {
      $id = $_POST["id"];
      $nombre = strtolower($_POST["nombre"]);
      $apellido = strtolower($_POST["apellido"]);
      $lNacimiento = strtolower($_POST["lNacimiento"]);
      $fNacimiento = $_POST["fNacimiento"];
      $partido = $_POST["partido"];
      $localidad = $_POST["localidad"];
      $domicilio = $_POST["domicilio"];
      $genero = $_POST["genero"];
      $tieneDoc = $_POST["tieneDoc"];
      $tipoDoc = $_POST["tipoDoc"];
      $nroDoc = $_POST["nroDoc"];
      $nroHC = $_POST["nroHC"];
      $nroCarpeta = $_POST["nroCarpeta"];
      $nroTel_cel = $_POST["nroTel_cel"];
      $obraSocial = $_POST["obraSocial"];
      $regionSanitaria = $_POST["regionSanitaria"];

      $repoPaciente = RepositorioPaciente::getInstance();

      //Valido que los campos no esten vacios y sean correctos
      if ($this->validarPacienteCompleto($nombre,$apellido,$lNacimiento,$fNacimiento,$partido,$localidad,
      $domicilio,$genero,$tieneDoc,$tipoDoc,$nroDoc,$nroHC,$nroCarpeta,$nroTel_cel,$obraSocial,$regionSanitaria,$id)){

        $result = $repoPaciente->actualizar_informacion($id,$apellido,$nombre,$fNacimiento,$lNacimiento,$localidad,$partido,
        $regionSanitaria,$domicilio,$genero,$tieneDoc,$tipoDoc,$nroDoc,$nroTel_cel,$nroHC,$nroCarpeta,$obraSocial);


        if ($result){
          TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Paciente guardado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }
}

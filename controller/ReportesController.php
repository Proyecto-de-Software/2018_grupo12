<?php
require('fpdf/PDFConsultas.php');
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

  public function pdfConsultas(){
    $pdf = new PDFConsultas();
    $pdf->AliasNbPages();
    // Carga de datos
    $data[] = array('Dni 12345678', 123456, '2018-11-14', 'Control por Guardia', 0);
    $pdf->SetFont('Arial','',14);
    $pdf->AddPage();
    $pdf->FancyTable($data);
    $pdf->AddPage();
    $pdf->FancyTable($data);
    $pdf->AddPage();
    $pdf->FancyTable($data);
    $pdf->Output("D","reporteDeConsultas.pdf",true);
  }
}

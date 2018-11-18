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
    $repoConsulta = RepositorioConsulta::getInstance();

    $pdf = new PDFConsultas();
    $pdf->AliasNbPages();
    // Carga de datos
    $data = $repoConsulta->obtener_todos();
    $pdf->SetFont('Arial','',14);
    $pdf->AddPage();
    $pdf->FancyTable($data["consultas"]);
    $pdf->Output("D","reporteDeConsultas.pdf",true);
  }

  public function cargarPagina(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $repoConsulta = RepositorioConsulta::getInstance();
      $view = new Reportes();

      $pagina = $_POST["pagina"];
      $agrupacion = $_POST["agrupacion"];
      $limite = $config->getLimite();
      $id = $_SESSION["id"];

      if ($agrupacion != "motivo" && $agrupacion != "genero" && $agrupacion != "localidad") {
        $agrupacion = "motivo";
      }

      //Identifico tipo de busqueda
      $resultado = $repoConsulta->obtener_todos_orden($agrupacion,$limite,$pagina);

      if (empty($resultado["consultas"])) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{

        $datos["consultas"] = $resultado["consultas"];

        $cantPaginasRestantes = (ceil( $resultado["total_consultas"] / $limite)) - $pagina;
        $view->cargarPagina($datos,$cantPaginasRestantes);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }
}

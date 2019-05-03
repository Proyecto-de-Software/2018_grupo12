<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tools\fpdf\PDFConsultas;
use App\Models\RepositorioConsulta;
use App\Models\RepositorioPermiso;
use App\Models\RepositorioConfiguracion;

class ReportesController extends Controller
{
    protected $repoConsulta;

    public function __construct(RepositorioConsulta $repoConsulta) {
        $this->repoConsulta = $repoConsulta;

        $this->middleware('auth');
        $this->middleware('authorize:reporte_index');
    }

    public function inicio(RepositorioPermiso $repoPermiso)
    {
        $id = session("id");

        $datos["modulos"] = $repoPermiso->modulos_id_usuario_admin($id,0);
        $datos["modulosAdministracion"] = $repoPermiso->modulos_id_usuario_admin($id,1);
        $datos["username"] = session("username");
        $datos["permisos"] = $repoPermiso->permisos_id_usuario_modulo($id,"reporte");
        $datos["porcentajesMotivo"] = $this->repoConsulta->motivo_porcentaje();
        $datos["porcentajesGenero"] = $this->repoConsulta->genero_porcentaje();
        $datos["porcentajesLocalidad"] = $this->repoConsulta->localidad_porcentaje();

        return view('reportes',$datos);
    }

    public function index(Request $request, RepositorioConfiguracion $repoConfiguracion)
    {
        $pagina = $request->input("pagina");
        $agrupacion = $request->input("agrupacion");
        $limite = $repoConfiguracion->getLimite();
        $id = session("id");

        if ($agrupacion != "motivo" && $agrupacion != "genero" && $agrupacion != "localidad") {
            $agrupacion = "motivo";
        }

        //Identifico tipo de busqueda
        $resultado = $this->repoConsulta->obtener_todos_orden($agrupacion,$limite,$pagina);

        if (empty($resultado["consultas"])) {
            return array('estado' => "no hay");
        }else{
            $datos["consultas"] = $resultado["consultas"];
            $cantPaginasRestantes = (ceil( $resultado["total_consultas"] / $limite)) - $pagina;

            return  array(
                'estado' => "si hay",
                'pagRestantes' => $cantPaginasRestantes,
                'contenido' => view('moduloReporte.cuerpoTablaConsultas', $datos)->render()
            );
        }
    }

    public function generarPDF(PDFConsultas $pdf)
    {
        $pdf->AliasNbPages();
        // Carga de datos
        $data = $this->repoConsulta->obtener_todos();
        $pdf->SetFont('Arial','',14);
        $pdf->AddPage();
        $pdf->FancyTable($data["consultas"]);
        $pdf->Output("D","reporteDeConsultas.pdf",true);
    }
}

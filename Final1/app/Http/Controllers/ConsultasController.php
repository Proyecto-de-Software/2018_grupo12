<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RepositorioConsulta;
use App\Models\RepositorioPermiso;
use App\Models\RepositorioMotivo;
use App\Models\RepositorioAcompanamiento;
use App\Models\RepositorioTratamiento;
use App\Models\RepositorioConfiguracion;
use App\Models\RepositorioPaciente;
use App\Models\RepositorioObraSocial;
use App\Models\RepositorioTipoDocumento;
use Validator;

class ConsultasController extends Controller
{
    protected $repoConsulta;
    protected $repoPermiso;
    protected $repoConfiguracion;
    protected $repoPaciente;

    public function __construct(RepositorioConsulta $repoConsulta, RepositorioPermiso $repoPermiso, RepositorioConfiguracion $repoConfiguracion,
                                RepositorioPaciente $repoPaciente)
    {
        $this->repoConsulta = $repoConsulta;
        $this->repoPermiso = $repoPermiso;
        $this->repoConfiguracion = $repoConfiguracion;
        $this->repoPaciente = $repoPaciente;

        $this->middleware('auth');
        $this->middleware('authorize:consulta_index')->only(['inicio','index']);
        $this->middleware('authorize:consulta_update')->only(['edit', 'update']);
        $this->middleware('authorize:consulta_show')->only(['show']);
        $this->middleware('authorize:consulta_new')->only(['store', 'pacientesParaConsulta', 'coordenadasDerivaciones']);
        $this->middleware('authorize:consulta_destroy')->only(['destroy']);
    }

    public function inicio(RepositorioMotivo $repoMotivo, RepositorioAcompanamiento $repoAcompanamiento, RepositorioTratamiento $repoTratamiento)
    {
        $id = session("id");

        $datos["modulos"] = $this->repoPermiso->modulos_id_usuario_admin($id,0);
        $datos["modulosAdministracion"] = $this->repoPermiso->modulos_id_usuario_admin($id,1);
        $datos["username"] = session("username");
        $datos["permisos"] = $this->repoPermiso->permisos_id_usuario_modulo($id,"consulta");
        $datos["motivos"] = $repoMotivo->obtener_todos();
        $datos["acompanamientos"] = $repoAcompanamiento->obtener_todos();
        $datos["tratamientos"] = $repoTratamiento->obtener_todos();
        return view('consultas', $datos);
    }

    public function pacientesParaConsulta(Request $request, RepositorioObraSocial $repoObraSocial, RepositorioTipoDocumento $repoTipoDoc)
    {
        $pagina = $request->input("pagina");
        $tipoBusqueda = $request->input("tipoBusqueda");
        $tipoDoc = $request->input("tipoDoc");
        $nroDoc = $request->input("nroDoc");
        $nroHistoriaClinica = $request->input("nroHistoriaClinica");

        $limite = $this->repoConfiguracion->getLimite();
        $id = session("id");

        //Identifico tipo de busqueda
        switch ($tipoBusqueda) {
            case 'dni':
                if (! $tipoDoc) {
                    $resultado = $this->repoPaciente->obtener_por_num_doc($nroDoc,$limite,$pagina);
                }else {
                    $resultado = $this->repoPaciente->obtener_por_datos_doc($tipoDoc,$nroDoc,$limite,$pagina);
                }
                break;
            case 'historia_clinica':
                $resultado = $this->repoPaciente->obtener_por_nro_historia_clinica($nroHistoriaClinica,$limite,$pagina);
                break;
            default:
                $resultado = $this->repoPaciente->obtener_todos_limite_pagina($limite,$pagina);
                break;
        }

        if (empty($resultado["pacientes"])) {
            return array('estado' => "no hay");
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
            $datos["permisos"] = $this->repoPermiso->permisos_id_usuario_modulo($id,"paciente");

            $cantPaginasRestantes = (ceil( $resultado["total_pacientes"] / $limite)) - $pagina;

            return array(
                'estado' => "si hay",
                'pagRestantes' => $cantPaginasRestantes,
                'contenido' => view('moduloConsulta.cuerpoTablaPacientes', $datos)->render()
            );
        }
    }

    public function coordenadasDerivaciones($id)
    {
        return $this->repoPaciente->coordenadas_derivaciones($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $pagina = $request->input("pagina");
        $tipoBusqueda = $request->input("tipoBusqueda");
        $tipoDoc = $request->input("tipoDoc");
        $nroDoc = $request->input("nroDoc");
        $nroHistoriaClinica = $request->input("nroHistoriaClinica");

        $limite = $this->repoConfiguracion->getLimite();
        $id = session("id");

      //Identifico tipo de busqueda
        switch ($tipoBusqueda) {
            case 'dni':
                if (! $tipoDoc) {
                    $resultado = $this->repoConsulta->obtener_numero_limite_pagina($nroDoc,$limite,$pagina);
                }else {
                    $resultado = $this->repoConsulta->obtener_documento_limite_pagina($tipoDoc,$nroDoc,$limite,$pagina);
                }
                break;
            case 'historia_clinica':
                $resultado = $this->repoConsulta->obtener_historia_limite_pagina($nroHistoriaClinica,$limite,$pagina);
                break;
            default:
                $resultado = $this->repoConsulta->obtener_todos_limite_pagina($limite,$pagina);
                break;
        }

        if (empty($resultado["consultas"])) {
            return array('estado' => "no hay");
        }else{
            $datos["consultas"] = $resultado["consultas"];
            $datos["permisos"] = $this->repoPermiso->permisos_id_usuario_modulo($id,"consulta");

            $cantPaginasRestantes = (ceil( $resultado["total_consultas"] / $limite)) - $pagina;

            return array(
                'estado' => "si hay",
                'pagRestantes' => $cantPaginasRestantes,
                'contenido' => view('moduloConsulta.cuerpoTablaConsultas', $datos)->render()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idPaciente = $request->input("id");
        $fecha = $request->input("fecha");
        $motivo = $request->input("motivo");
        $derivacion = $request->input("derivacion");
        $internacion = $request->input("internacion");
        $tratamiento = $request->input("tratamiento");
        $acompanamiento = $request->input("acompanamiento");
        $articulacion = $request->input("articulacion");
        $diagnostico = $request->input("diagnostico");
        $observaciones = $request->input("observaciones");

        $validador = Validator::make($request->all(),
        [
            'id' => 'required | numeric',
            'fecha' => 'required | date | before_or_equal:' . date('Y-m-d'),
            'motivo' => 'required | numeric',
            'derivacion' => 'nullable | numeric',
            'tratamiento' => 'nullable | numeric',
            'acompanamiento' => 'nullable | numeric',
            'diagnostico' => 'required | max:255',
            'internacion' => 'required | numeric | regex:/^[01]$/',
            'articulacion' => 'nullable | max:255',
            'observaciones' => 'nullable | max:255'
        ],
        [
            'id.required' => 'Seleccione un paciente de la lista',
            'id.numeric' => 'Paciente incorrecto',
            'required' => 'El campo :attribute es obligatorio',
            'numeric' => 'El campo :attribute debe contener solo numeros',
            'internacion.regex' => 'El campo :attribute es incorrecto',
            'before_or_equal' => 'La fecha del campo :attribute debe ser menor o igual a la actual',
            'max' => 'El campo :attribute debe tener un maximo de 255 caracteres',
            'date' => 'El campo :attribute es incorrecto'
        ],
        [
            'articulacion' => 'articulacion con otras instituciones',
            'tratamiento' => 'tratamiento farmacologico',
            'acompanamiento' => 'acompañamiento'
        ]);

        if ($validador->fails()) {
            return array('estado' => "error", 'mensaje' => $validador->errors()->first());
        }

        if ($this->repoConsulta->insertar_consulta($idPaciente, $fecha, $motivo, $derivacion, $articulacion,
            (int)$internacion, $diagnostico, $observaciones, $tratamiento, $acompanamiento)){
            return array('estado' => "success", 'mensaje' => "Consulta guardada correctamente");
        }else {
            return array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $id) {
            return array('estado' => "error", 'mensaje'=> "Consulta no especificada");
        }

        $consulta = $this->repoConsulta->obtener_info_completa($id);

        return array(
            "contenido" => view('moduloConsulta.detalleConsulta',$consulta)->render(),
            "estado" => "success"
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! $id) {
          return array('estado' => "error", 'mensaje'=> "Consulta no especificada");
        }

        $consulta = $this->repoConsulta->obtener_info_completa($id);

        $datos["estado"] = "success";
        $datos["tratamiento"] = $consulta["tratamiento_farmacologico_id"];
        $datos["articulacion"] = $consulta["articulacion_con_instituciones"];
        $datos["diagnostico"] = $consulta["diagnostico"];
        $datos["observaciones"] = $consulta["observaciones"];

        return $datos;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tratamiento = $request->input("tratamiento");
        $articulacion = $request->input("articulacion");
        $diagnostico = $request->input("diagnostico");
        $observaciones = $request->input("observaciones");

        $validador = Validator::make($request->all(),
        [
            'diagnostico' => 'required | max:255',
            'articulacion' => 'nullable | max:255',
            'observaciones' => 'nullable | max:255'
        ],
        [
            'id.required' => 'Consulta no especificada',
            'id.numeric' => 'Consulta incorrecta',
            'required' => 'El campo :attribute es obligatorio',
            'numeric' => 'El campo :attribute debe contener solo numeros',
            'max' => 'El campo :attribute debe tener un maximo de 255 caracteres'
        ],
        [
            'articulacion' => 'articulacion con otras instituciones'
        ]);

        if ($validador->fails()) {
            return array('estado' => "error", 'mensaje' => $validador->errors()->first());
        }

        $this->repoConsulta->actualizar_consulta($id,$tratamiento,$articulacion,$diagnostico,$observaciones);

        return array('estado' => "success", 'mensaje' => "Consulta guardada correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $id) {
            return array('estado' => "error", 'mensaje'=> "Consulta no especificada");
        }

        if ($this->repoConsulta->eliminar_consulta($id)) {
            return array('estado' => "success", 'mensaje'=> "Consulta eliminada correctamente");
        }else {
            return array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde");
        }
    }
}

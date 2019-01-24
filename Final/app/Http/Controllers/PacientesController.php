<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RepositorioPaciente;
use App\Models\RepositorioObraSocial;
use App\Models\RepositorioTipoDocumento;
use App\Models\RepositorioPermiso;
use App\Models\RepositorioConfiguracion;
use App\Models\Paciente;
use Validator;

class PacientesController extends Controller
{
    protected $repoPaciente;
    protected $repoObraSocial;
    protected $repoTipoDocumento;
    protected $repoPermiso;
    protected $repoConfiguracion;

    public function __construct(RepositorioPaciente $repoPaciente, RepositorioObraSocial $repoObraSocial,
                                RepositorioTipoDocumento $repoTipoDocumento, RepositorioPermiso $repoPermiso,
                                RepositorioConfiguracion $repoConfiguracion)
    {
        $this->repoPaciente = $repoPaciente;
        $this->repoObraSocial = $repoObraSocial;
        $this->repoTipoDocumento = $repoTipoDocumento;
        $this->repoPermiso = $repoPermiso;
        $this->repoConfiguracion = $repoConfiguracion;

        $this->middleware('auth');
        $this->middleware('authorize:paciente_index')->only(['inicio','index']);
        $this->middleware('authorize:paciente_destroy')->only(['pacienteTieneConsultas','destroy']);
        $this->middleware('authorize:paciente_show')->only(['show']);
        $this->middleware('authorize:paciente_new')->only(['agregarPacienteSimple','agregarPacienteCompleto']);
        $this->middleware('authorize:paciente_update')->only(['edit','update']);

    }

    public function inicio()
    {
        $id = session("id");

        $datos["modulos"] = $this->repoPermiso->modulos_id_usuario_admin($id,0);
        $datos["modulosAdministracion"] = $this->repoPermiso->modulos_id_usuario_admin($id,1);
        $datos["username"] = session("username");
        $datos["permisos"] = $this->repoPermiso->permisos_id_usuario_modulo($id,"paciente");

        return view('pacientes', $datos);
    }

    //Uso interno, para obtener el validador en agregarPacienteCompleto y update
    public function obtenerValidador($request)
    {
        return Validator::make($request->all(),
        [
            'nombre' => 'required | regex:/^[a-zA-Z ]+$/',
            'apellido' => 'required | regex:/^[a-zA-Z ]+$/',
            'fNacimiento' => 'required | date | before:' . date('Y-m-d'),
            'domicilio' => 'required',
            'genero' => 'required',
            'tieneDoc' => 'required | numeric | regex:/^[01]$/',
            'tipoDoc' => 'required',
            'nroDoc' => 'required | numeric | max:9999999999 | min:10000000',
            'nroHC' => 'nullable | min:1 | max:999999',
            'nroCarpeta' => 'nullable | min:1 | max:99999',
            'nroTel_cel' => 'nullable | regex:/^[0-9 +]+$/'
        ],
        [
            'required' => 'El campo :attribute es obligatorio',
            'numeric' => 'El campo :attribute debe contener solo numeros',
            'nroDoc.max' => 'El campo :attribute debe tener un maximo de 10 numeros',
            'nroDoc.min' => 'El campo :attribute debe tener un minimo de 8 numeros',
            'nroHC.max' => 'El campo :attribute debe tener un maximo de 6 numeros',
            'nroHC.min' => 'El campo :attribute debe tener un minimo de 1 numero',
            'nroCarpeta.max' => 'El campo :attribute debe tener un maximo de 6 numeros',
            'nroCarpeta.min' => 'El campo :attribute debe tener un minimo de 1 numero',
            'unique' => 'El campo :attribute ya esta registrado',
            'tieneDoc.regex' => 'El campo :attribute es incorrecto',
            'nombre.regex' => 'El campo :attribute debe contener solo letras',
            'apellido.regex' => 'El campo :attribute debe contener solo letras',
            'nroTel_cel.regex' => 'El campo :attribute es incorrecto',
            'alpha' => 'El campo :attribute debe contener solo letras',
            'date' => 'El campo :atttribute es incorrecto',
            'before' => 'La fecha del campo :attribute debe ser menor a la actual'
        ],
        [
            'fNacimiento' => 'fecha de nacimiento',
            'tieneDoc' => 'tiene en su poder su documento',
            'tipoDoc' => 'tipo de documento',
            'nroDoc' => 'numero de documento',
            'nroHC' => 'numero de historia clinica',
            'nroCarpeta' => 'numero de carpeta',
            'nroTel_cel' => 'Numero de telefono o celular'
        ]);
    }

    public function pacienteTieneConsultas($id)
    {
        return array(
            'estado' => "success",
            'tieneConsultas'=> $this->repoPaciente->paciente_tiene_consultas($id)
        );
    }

    public function agregarPacienteSimple(Request $request)
    {
        $nroHC = $request->input("nroHC");

        $validador = Validator::make($request->all(),
        [
            'nroHC' => 'required | numeric | max:999999 | unique:paciente,nro_historia_clinica',
        ],
        [
            'required' => 'El campo :attribute es obligatorio',
            'numeric' => 'El campo :attribute debe contener solo numeros',
            'max' => 'El campo :attribute debe tener un maximo de 6 numeros',
            'unique' => 'El campo :attribute ya esta registrado'
        ],
        [
            'nroHC' => 'numero de historia clinica'
        ]);

        if ($validador->fails()) {
            return array('estado' => "error", 'mensaje' => $validador->errors()->first());
        }

        if ($this->repoPaciente->insertar_nn($nroHC)) {
            return array('estado' => "success", 'mensaje' => "Paciente guardado correctamente");
        }else {
            return array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde");
        }
    }

    public function agregarPacienteCompleto(Request $request)
    {
        $nombre = strtolower($request->input("nombre"));
        $apellido = strtolower($request->input("apellido"));
        $lNacimiento = strtolower($request->input("lNacimiento"));
        $fNacimiento = $request->input("fNacimiento");
        $partido = $request->input("partido");
        $localidad = $request->input("localidad");
        $domicilio = $request->input("domicilio");
        $genero = $request->input("genero");
        $tieneDoc = $request->input("tieneDoc");
        $tipoDoc = $request->input("tipoDoc");
        $nroDoc = $request->input("nroDoc");
        $nroHC = $request->input("nroHC");
        $nroCarpeta = $request->input("nroCarpeta");
        $nroTel_cel = $request->input("nroTel_cel");
        $obraSocial = $request->input("obraSocial");
        $regionSanitaria = $request->input("regionSanitaria");

        $validador = $this->obtenerValidador($request);

        if ($validador->fails()) {
            return array('estado' => "error", 'mensaje' => $validador->errors()->first());
        }

        if ($this->repoPaciente->existe_doc($tipoDoc, $nroDoc)) {
            return array('estado' => "error", 'mensaje' => "El numero de documento ya existe");
        }

        if ($nroHC && $this->repoPaciente->existe_historia_clinica($nroHC)) {
            return array('estado' => "error", 'mensaje' => "El numero de historia clinica ya existe");
        }

        //Valido que los campos no esten vacios y sean correctos

        $paciente = new Paciente('',$apellido,$nombre,$fNacimiento,$lNacimiento,$localidad,$partido,$regionSanitaria,
                    $domicilio,$genero,$tieneDoc,$tipoDoc,$nroDoc,$nroTel_cel,$nroHC,$nroCarpeta,$obraSocial,0);

        if ($this->repoPaciente->insertar_paciente($paciente)){
            return array('estado' => "success", 'mensaje' => "Paciente guardado correctamente");
        }else {
            return array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde");
        }
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
        $nombre = strtolower($request->input("nombre"));
        $apellido = strtolower($request->input("apellido"));
        $tipoDoc = $request->input("tipoDoc");
        $nroDoc = $request->input("nroDoc");
        $nroHistoriaClinica = $request->input("nroHistoriaClinica");
        $limite = $this->repoConfiguracion->getLimite();
        $id = session("id");

        //Identifico tipo de busqueda
        switch ($tipoBusqueda) {
            case 'nombre_y_apellido':
                if ($nombre &&  !$apellido) {
                    $resultado = $this->repoPaciente->obtener_por_nombre($nombre, $limite, $pagina);
                }elseif (!$nombre && $apellido) {
                    $resultado = $this->repoPaciente->obtener_por_apellido($apellido, $limite, $pagina);
                }else {
                    $resultado = $this->repoPaciente->obtener_por_nombre_y_apellido($nombre,$apellido,$limite,$pagina);
                }
                break;
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

                $obraSocialPaciente = $this->repoObraSocial->obtener_por_id($idObraSocial);
                if ($obraSocialPaciente) {
                    $paciente->setNombreObraSocial($obraSocialPaciente->getNombre());
                }

                $tipoDocPaciente = $this->repoTipoDocumento->obtener_por_id($idTipoDoc);
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
                'contenido' => view('moduloPaciente.cuerpoTablaPacientes', $datos)->render()
            );
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->repoPaciente->eliminar_paciente($id)) {
           return array('estado' => "success", 'mensaje'=> "Paciente eliminado correctamente");
        }else {
           return array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde");
        }
    }
}

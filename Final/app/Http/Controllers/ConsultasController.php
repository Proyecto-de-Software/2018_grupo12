<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RepositorioConsulta;
use App\Models\RepositorioPermiso;
use App\Models\RepositorioMotivo;
use App\Models\RepositorioAcompanamiento;
use App\Models\RepositorioTratamiento;
use App\Models\RepositorioConfiguracion;

class ConsultasController extends Controller
{
    protected $repoConsulta;
    protected $repoPermiso;
    protected $repoConfiguracion;

    public function __construct(RepositorioConsulta $repoConsulta, RepositorioPermiso $repoPermiso, RepositorioConfiguracion $repoConfiguracion) {
        $this->repoConsulta = $repoConsulta;
        $this->repoPermiso = $repoPermiso;
        $this->repoConfiguracion = $repoConfiguracion;

        $this->middleware('auth');
        $this->middleware('authorize:consulta_index')->only(['inicio','index']);
        $this->middleware('authorize:consulta_update')->only(['edit', 'update']);
        $this->middleware('authorize:consulta_new')->only(['store']);
        $this->middleware('authorize:consulta_administrarRoles')->only(['panelRoles', 'agregarRol', 'quitarRol']);
        $this->middleware('authorize:consulta_activarBloquear')->only(['activarUsuario', 'bloquearUsuario']);
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
        //
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
        //
    }
}

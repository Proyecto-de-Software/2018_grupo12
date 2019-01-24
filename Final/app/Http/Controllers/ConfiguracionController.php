<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RepositorioPermiso;
use App\Models\RepositorioConfiguracion;
use Validator;

class ConfiguracionController extends Controller
{
    protected $repoConfiguracion;
    protected $repoPermiso;

    public function __construct(RepositorioPermiso $repoPermiso, RepositorioConfiguracion $repoConfiguracion)
    {
        $this->repoPermiso = $repoPermiso;
        $this->repoConfiguracion = $repoConfiguracion;

        $this->middleware('auth');
        $this->middleware('authorize:configuracion_index')->only(['inicio']);
        $this->middleware('authorize:configuracion_update')->only(['guardarConfiguracion']);
    }

    public function inicio()
    {
        $id = session("id");

        $datos = $this->repoConfiguracion->obtener_configuracion();
        $datos["modulos"] = $this->repoPermiso->modulos_id_usuario_admin($id,0);
        $datos["modulosAdministracion"] = $this->repoPermiso->modulos_id_usuario_admin($id,1);
        $datos["username"] = session("username");
        $datos["permisos"] = $this->repoPermiso->permisos_id_usuario_modulo($id,"configuracion");

        return view("configuracion", $datos);
    }

    public function guardarConfiguracion(Request $request)
    {
        $titulo = $request->input("titulo");
        $descripcion = $request->input("descripcion");
        $email = $request->input("email");
        $limite = $request->input("limite");
        $habilitado = $request->input("habilitado");

        $validador = Validator::make($request->all(),
        [
            'titulo' => 'required',
            'descripcion' => 'required',
            'email' => 'required | email',
            'limite' => 'required | alpha_num',
            'habilitado' => 'required | regex:/^[01]$/',
        ],
        [
            'required' => 'El campo :attribute es obligatorio',
            'email' => 'Email ingresado es incorrecto',
            'alpha_num' => 'El campo :attribute debe contener solo numeros',
            'habilitado.regex' => 'El campo :attribute es incorrecto'
        ],
        [
            'mail' => 'mail de contacto',
            'limite' => 'cantidad de elementos por pagina',
            'habilitado' => 'sitio habilitado'
        ]);

        if ($validador->fails()) {
            return array('estado' => "error", 'mensaje' => $validador->errors()->first());
        }

        //Guardo la configuracion nueva
        $this->repoConfiguracion->setTitulo($titulo);
        $this->repoConfiguracion->setDescripcion($descripcion);
        $this->repoConfiguracion->setEmail($email);
        $this->repoConfiguracion->setLimite($limite);
        $this->repoConfiguracion->habilitacion($habilitado);

        return array('estado' => "success", 'mensaje'=> "Los datos se guardaron correctamente");
    }
}

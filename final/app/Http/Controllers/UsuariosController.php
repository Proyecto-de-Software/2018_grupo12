<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RepositorioUsuario;
use App\Models\RepositorioRol;
use App\Models\RepositorioPermiso;
use App\Models\RepositorioConfiguracion;
use App\Models\RepositorioUsuarioTieneRol;
use App\Models\Usuario;
use Validator;

class UsuariosController extends Controller
{
    protected $repoUsuario;
    protected $repoRol;
    protected $repoPermiso;
    protected $repoConfiguracion;
    protected $repoUsuarioTieneRol;


    public function __construct(RepositorioUsuario $repoUsuario, RepositorioRol $repoRol,
                                RepositorioPermiso $repoPermiso, RepositorioConfiguracion $repoConfiguracion,
                                RepositorioUsuarioTieneRol $repoUsuarioTieneRol)
    {
        $this->repoUsuario = $repoUsuario;
        $this->repoRol = $repoRol;
        $this->repoPermiso = $repoPermiso;
        $this->repoConfiguracion = $repoConfiguracion;
        $this->repoUsuarioTieneRol = $repoUsuarioTieneRol;

        $this->middleware('auth');
        $this->middleware('authorize:usuario_index')->only(['inicio','index']);
        $this->middleware('authorize:usuario_update')->only(['edit', 'update']);
        $this->middleware('authorize:usuario_new')->only(['store']);
        $this->middleware('authorize:usuario_administrarRoles')->only(['panelRoles', 'agregarRol', 'quitarRol']);
        $this->middleware('authorize:usuario_activarBloquear')->only(['activarUsuario', 'bloquearUsuario']);
    }

    public function inicio()
    {
        $id = session("id");

        $datos["modulos"] = $this->repoPermiso->modulos_id_usuario_admin($id,0);
        $datos["modulosAdministracion"] = $this->repoPermiso->modulos_id_usuario_admin($id,1);
        $datos["username"] = session("username");
        $datos["permisos"] = $this->repoPermiso->permisos_id_usuario_modulo($id,"usuario");

        return view('usuarios', $datos);
    }

    public function bloquearUsuario($id)
    {
        if ($id == session("id")) {
            return array('estado' => "auto_bloqueo");
        }

        if ($this->repoUsuarioTieneRol->usuario_es_admin($id) && ($this->repoUsuarioTieneRol->usuarios_activos_admin() == 1)) {
            return array('estado' => "un_solo_admin");
        }

        if ($this->repoUsuario->bloquear_activar($id,0)) {
            return array('estado' => "bloqueado");
        }else {
            return array('estado' => "error");
        }
    }

    public function activarUsuario($id)
    {
        if ($id == session("id")) {
            return array('estado' => "auto_activacion");
        }

        if ($this->repoUsuario->bloquear_activar($id,1)) {
            return array('estado' => "activado");
        }else {
            return array('estado' => "error");
        }
    }

    public function panelRoles($id)
    {
        if (! $id) {
            return array('estado' => "error", 'mensaje' => "Usuario no especificado");
        }

        $usuario = $this->repoUsuario->obtener_usuario_por_id($id);
        $roles = $this->repoRol->obtener_todos_los_roles();

        if ($usuario) {
            $usuario->setRoles($this->repoRol->obtener_por_id_usuario($usuario->getId()));

            return array(
                'estado' => "success",
                'contenido' => view("moduloUsuario.cuerpoPanelAdministracionRoles",array('usuario' => $usuario, 'roles' => $roles ))->render()
            );
        }else {
            return array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde");
        }
    }

    public function agregarRol($id, $idRol)
    {
        if (! ($id && $idRol)) {
            return array('estado' => "error", 'mensaje' => "Usuario o rol no especificados");
        }

        if ($idRol == "no seleccionado") {
            return array('estado' => "error", 'mensaje' => "No se selecciono ningun rol");
        }

        if ($this->repoUsuarioTieneRol->relacion_existe($id,$idRol,0)) {
            return array('estado' => "error", 'mensaje' => "El usuario ya tiene asignado este rol");
        }

        if ($this->repoUsuarioTieneRol->crearRelacion($id,$idRol)) {
            return array('estado' => "success", 'mensaje' => "Rol agregado correctamente");
        }else {
            return array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde");
        }
    }

    public function quitarRol($id, $idRol)
    {
        if (! ($id && $idRol)) {
            return array('estado' => "error", 'mensaje' => "Usuario o rol no especificados");
        }

        $rol = $this->repoRol->obtener_por_id($idRol);
        $activo = $this->repoUsuario->obtener_usuario_por_id($id)->getActivo();

        if ($activo && ($rol->getNombre() == "Administrador") && ($this->repoUsuarioTieneRol->usuarios_activos_admin() == 1)) {
            return array('estado' => "error", 'mensaje' => "Queda un unico administrador, no puedes quitarle el rol");
        }

        if ($this->repoUsuarioTieneRol->eliminarRelacion($id, $idRol)) {
            return array('estado' => "success", 'mensaje' => "Rol quitado correctamente");
        }else {
            return array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde");
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
        $username = strtolower($request->input("username"));
        $estado = $request->input("estado");
        $limite = $this->repoConfiguracion->getLimite();
        $id = session("id");

        $datos["permisos"] = $this->repoPermiso->permisos_id_usuario_modulo($id,"usuario");

        //Identifico tipo de busqueda
        if ($username) {
            if ($estado == "no aplica") {
                $resultado = $this->repoUsuario->obtener_todos_limite_pagina_like($limite,$pagina,$username);
            }else{
                $resultado  = $this->repoUsuario->obtener_actblo_limite_pagina_like($limite,$pagina,$username,$estado);
            }
        }elseif ($estado != "no aplica") {
            if ($estado == "1") {
                $resultado  = $this->repoUsuario->obtener_activos_limite_pagina($limite,$pagina);
            } else {
                $resultado  = $this->repoUsuario->obtener_bloqueados_limite_pagina($limite,$pagina);
            }
        }else {
            $resultado  = $this->repoUsuario->obtener_todos_limite_pagina($limite,$pagina);
        }

        if (empty($resultado["usuarios"])) {
            return array('estado' => "no hay");
        }else{
            foreach ($resultado["usuarios"] as $usuario) {
                $usuario->setRoles($this->repoRol->obtener_por_id_usuario($usuario->getId()));
            }

            $datos["usuarios"] = $resultado["usuarios"];
            $cantPaginasRestantes = (ceil( $resultado["total_usuarios"] / $limite)) - $pagina;

            return array(
                'estado' => "si hay",
                'pagRestantes' => $cantPaginasRestantes,
                'contenido' => view('moduloUsuario.cuerpoTablaUsuarios', $datos)->render()
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
        $nombre = strtolower($request->input("nombre"));
        $apellido = strtolower($request->input("apellido"));
        $nombreDeUsuario = $request->input("nombreDeUsuario");
        $contrasena = $request->input("contrasena");
        $email = strtolower($request->input("email"));

        $validador = Validator::make($request->all(),
        [
            'nombre' => 'required | regex:/^[a-zA-Z ]+$/',
            'apellido' => 'required | regex:/^[a-zA-Z ]+$/',
            'contrasena' => 'required | min:8',
            'email' => 'required | email',
            'nombreDeUsuario' => 'required | regex:/^[a-z0-9_]+$/ | unique:usuario,username'
        ],
        [
            'required' => 'El campo :attribute es obligatorio',
            'email' => 'Email ingresado es incorrecto',
            'nombre.regex' => 'El campo :attribute debe contener solo letras',
            'apellido.regex' => 'El campo :attribute debe contener solo letras',
            'nombreDeUsuario.regex' => 'El campo :attribute permite solo letras minusculas, numeros y guion bajo',
            'min' => 'El campo :attribute debe tener por lo menos 8 caracteres',
            'unique' => 'El nombre de usuario ya esta registrado'
        ],
        [
            'nombreDeUsuario' => 'nombre de usuario',
            'contrasena' => 'contraseña'
        ]);

        if ($validador->fails()) {
            return array('estado' => "error", 'mensaje' => $validador->errors()->first());
        }

        $contrasena = password_hash($contrasena,PASSWORD_DEFAULT);
        $usuario = new Usuario("",$email,$nombreDeUsuario,$contrasena,"","","",$nombre,$apellido,"");
        if ($this->repoUsuario->insertar_usuario($usuario)){
            return array('estado' => "success", 'mensaje' => "Usuario guardado correctamente");
        }else {
            return array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = $this->repoUsuario->obtener_usuario_por_id($id);

        return array(
            'estado' => "success",
            'contenido' => view('moduloUsuario.formularioModificacionUsuario', array('usuario' => $usuario ))->render()
        );
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
        $nombre = strtolower($request->input("nombre"));
        $apellido = strtolower($request->input("apellido"));
        $contrasena = $request->input("contrasena");
        $email = strtolower($request->input("email"));

        $validador = Validator::make($request->all(),
        [
            'nombre' => 'required | alpha',
            'apellido' => 'required | alpha',
            'email' => 'required | email',
            'contrasena' => 'nullable | min:8'
        ],
        [
            'required' => 'El campo :attribute es obligatorio',
            'email' => 'Email ingresado es incorrecto',
            'alpha' => 'El campo :attribute debe contener solo letras',
            'nombreDeUsuario.regex' => 'El campo :attribute permite solo letras minusculas, numeros y guion bajo',
            'min' => 'El campo :attribute debe tener por lo menos 8 caracteres'
        ],
        [
            'nombreDeUsuario' => 'nombre de usuario',
            'contrasena' => 'contraseña'
        ]);

        if ($validador->fails()) {
            return array('estado' => "error", 'mensaje' => $validador->errors()->first());
        }

        if ($contrasena){
            //actualizar contraseña
            $contrasena = password_hash($contrasena,PASSWORD_DEFAULT);
            $this->repoUsuario->actualizar_password_usuario($id, $contrasena);
        }

        $this->repoUsuario->actualizar_informacion_usuario($id,$email,$nombre,$apellido);

        return array('estado' => "success", 'mensaje' => "Usuario modificado correctamente");
    }
}

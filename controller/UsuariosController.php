<?php

class UsuariosController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
        self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function loguear(){
    try{
      $repoUsuario = RepositorioUsuario::getInstance();
      if(!($_POST['usuario'] && $_POST['contrasena'])){
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete todos los campos"));
      }else{
        if($repoUsuario->username_existe($_POST['usuario'])){
          $usuario = $repoUsuario->obtener_usuario_por_username($_POST['usuario']);
          //1 activo SI   .... 0 Bloqueado
          if(password_verify($_POST['contrasena'], $usuario->getPassword()) ){
            $_SESSION['userName']=$usuario->getUsername();
            $_SESSION['id']=$usuario->getId();
            TwigView::jsonEncode(array('estado' => "success"));
          }else{
            TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Usuario y contraseña incorrectos"));
          }
        }else{
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Usuario y contraseña incorrectos"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }

  public function logout() {
    session_destroy();
    $view = new Inicio();
    $view->show();
  }

  public function redirectUsuarios(){
    try {
      $repoUsuario = RepositorioUsuario::getInstance();
      $repoRol = RepositorioRol::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Usuarios();

      $id = $_SESSION["id"];
      $limite = RepositorioConfiguracion::getInstance()->getLimite();
      $usuarios = $repoUsuario->obtener_todos_limite_pagina($limite,1);

      foreach ($usuarios as $usuario) {
        $usuario->setRoles($repoRol->obtener_por_id_usuario($usuario->getId()));
      }

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"usuario");
      $datos["usuarios"] = $usuarios;

      $view->show($datos);
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function cargarPagina(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $repoUsuario = RepositorioUsuario::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Usuarios();

      $pagina = $_POST["pagina"];
      $username = strtolower($_POST["username"]);
      $estado = $_POST["estado"];
      $limite = $config->getLimite();
      $id = $_SESSION["id"];

      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"usuario");

      //Identifico tipo de busqueda
      if ($username) {
        if ($estado == "no aplica") {
          $usuarios = $repoUsuario->obtener_todos_limite_pagina_like($limite,$pagina,$username);
        }else{
          $usuarios = $repoUsuario->obtener_actblo_limite_pagina_like($limite,$pagina,$username,$estado);
        }
      }elseif ($estado != "no aplica") {
        if ($estado == "1") {
          $usuarios = $repoUsuario->obtener_activos_limite_pagina($limite,$pagina);
        } else {
          $usuarios = $repoUsuario->obtener_bloqueados_limite_pagina($limite,$pagina);
        }
      }else {
        $usuarios = $repoUsuario->obtener_todos_limite_pagina($limite,$pagina);
      }

      if (empty($usuarios)) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{
        $repoRol = RepositorioRol::getInstance();
        foreach ($usuarios as $usuario) {
          $usuario->setRoles($repoRol->obtener_por_id_usuario($usuario->getId()));
        }

        $datos["usuarios"] = $usuarios;
        $view->cargarPagina($datos);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function bloquearUsuario(){
    try {
      $repoUsuario = RepositorioUsuario::getInstance();

      $id = $_POST["id"];

      if ($repoUsuario->bloquear_usuario($id)) {
        TwigView::jsonEncode(array('estado' => "bloqueado"));
      }else {
        TwigView::jsonEncode(array('estado' => "error"));
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function activarUsuario(){
    try {
      $repoUsuario = RepositorioUsuario::getInstance();

      $id = $_POST["id"];

      if ($repoUsuario->activar_usuario($id)) {
        TwigView::jsonEncode(array('estado' => "activado"));
      }else {
        TwigView::jsonEncode(array('estado' => "error"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function agregarUsuario(){


    try {
      $nombre = strtolower($_POST["nombre"]);
      $apellido = strtolower($_POST["apellido"]);
      $nombreDeUsuario = strtolower($_POST["nombreDeUsuario"]);
      $contrasena = $_POST["contrasena"];
      $email = strtolower($_POST["email"]);

      $repoUsuario = RepositorioUsuario::getInstance();

      //Valido que el email sea correcto y que los campos no esten vacios
      if (!($nombre && $apellido && $nombreDeUsuario && $contrasena && $email)) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete todos los campos"));
      }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Email ingresado es incorrecto"));
      }elseif ($repoUsuario->username_existe($nombreDeUsuario)) {
        //Valido que no exista el nombre de usuario
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El nombre de usuario ya esta registrado"));
      }elseif (strlen($contrasena) < 8) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "La contraseña debe tener por lo menos 8 caracteres"));
      }else{
        $contrasena = password_hash($contrasena,PASSWORD_DEFAULT);
        $usuario = new Usuario("",$email,$nombreDeUsuario,$contrasena,"","","",$nombre,$apellido,"");
        if ($repoUsuario->insertar_usuario($usuario)){
          TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Usuario guardado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

  public function formularioModificacionUsuario(){
    try {
        $id = $_POST["id"];

        $repoUsuario = RepositorioUsuario::getInstance();
        $view = new Usuarios();

        $usuario = $repoUsuario->obtener_usuario_por_id($id);

        $view->formularioModificacionUsuario($usuario);
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function modificarUsuario(){
    try {
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $contrasena = $_POST["contrasena"];
        $email = $_POST["email"];

        $repoUsuario = RepositorioUsuario::getInstance();

        //Valido campos
        if (!($nombre && $apellido && $email)) {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete todos los campos"));
        }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Email ingresado es incorrecto"));
        }else{
          if ($contrasena){
            if (strlen($contrasena) < 8) {
              TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "La contraseña debe tener al menos 8 caracteres"));
            }else {
              //actualizar contraseña
              $contrasena = password_hash($contrasena,PASSWORD_DEFAULT);
              $repoUsuario->actualizar_informacion_usuario($id,$email,$nombre,$apellido);
              $repoUsuario->actualizar_password_usuario($id, $contrasena);
              TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Usuario modificado correctamente"));
            }
          }else{
            $repoUsuario->actualizar_informacion_usuario($id,$email,$nombre,$apellido);
            TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Usuario modificado correctamente"));
          }
        }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

  public function cuerpoPanelAdministracionRoles(){
    try {
      $id = $_POST["id"];

      $repoUsuario = RepositorioUsuario::getInstance();
      $repoRol = RepositorioRol::getInstance();
      $view = new Usuarios();

      $usuario = $repoUsuario->obtener_usuario_por_id($id);
      $roles = $repoRol->obtener_todos_los_roles();

      if ($usuario) {
        $usuario->setRoles($repoRol->obtener_por_id_usuario($usuario->getId()));
        $view->cuerpoPanelAdministracionRoles($usuario,$roles);
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

  public function agregarRol(){
    try {
      $id = $_POST["id"];
      $idRol = $_POST["idRol"];

      $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

      if ($idRol == "no seleccionado") {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se selecciono ningun rol"));
      }elseif ($repoUsuarioTieneRol->relacion_existe($id,$idRol,0)) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El usuario ya tiene asignado este rol"));
      }elseif ($repoUsuarioTieneRol->crearRelacion($id,$idRol)) {
        TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Rol agregado correctamente"));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

  public function quitarRol(){
    try {
      $id = $_POST["id"];
      $idRol = $_POST["idRol"];

      $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

      if ($repoUsuarioTieneRol->eliminarRelacion($id, $idRol)) {
        TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Rol quitado correctamente"));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

  function tienePermisos($nombrePermiso){
    //revisa la session
    if(isset($_SESSION) ){

        $repoPermisos = RepositorioPermiso::getInstance();

        if($repoPermisos->id_usuario_tiene_permiso($_SESSION['id'], $nombrePermiso)){
            return true;
        }
    }
        //no esta logueado
    return false;
  }



}
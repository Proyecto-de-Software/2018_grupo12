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
        if(!preg_match("/[A-ZÑ ]/", $_POST['usuario']) && $repoUsuario->username_existe($_POST['usuario'])){
          $usuario = $repoUsuario->obtener_usuario_por_username($_POST['usuario']);
          //1 activo SI   .... 0 Bloqueado
          if(password_verify($_POST['contrasena'], $usuario->getPassword()) ){
            if ($usuario->getActivo()) {
              $_SESSION['userName']=$usuario->getUsername();
              $_SESSION['id']=$usuario->getId();
              TwigView::jsonEncode(array('estado' => "success"));
            }else {
              TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El usuario se encuentra bloqueado"));
            }
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
    $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
    $view = new Inicio();
    $view->show($datos);
  }

  public function redirectUsuarios(){
    try {
      $repoUsuario = RepositorioUsuario::getInstance();
      $repoRol = RepositorioRol::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Usuarios();

      $id = $_SESSION["id"];
      $limite = RepositorioConfiguracion::getInstance()->getLimite();
      $resultado = $repoUsuario->obtener_todos_limite_pagina($limite,1);
      $usuarios = $resultado["usuarios"];

      foreach ($usuarios as $usuario) {
        $usuario->setRoles($repoRol->obtener_por_id_usuario($usuario->getId()));
      }

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"usuario");
      $datos["usuarios"] = $usuarios;
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
          $resultado = $repoUsuario->obtener_todos_limite_pagina_like($limite,$pagina,$username);
        }else{
          $resultado  = $repoUsuario->obtener_actblo_limite_pagina_like($limite,$pagina,$username,$estado);
        }
      }elseif ($estado != "no aplica") {
        if ($estado == "1") {
          $resultado  = $repoUsuario->obtener_activos_limite_pagina($limite,$pagina);
        } else {
          $resultado  = $repoUsuario->obtener_bloqueados_limite_pagina($limite,$pagina);
        }
      }else {
        $resultado  = $repoUsuario->obtener_todos_limite_pagina($limite,$pagina);
      }

      if (empty($resultado["usuarios"])) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{
        $repoRol = RepositorioRol::getInstance();
        foreach ($resultado["usuarios"] as $usuario) {
          $usuario->setRoles($repoRol->obtener_por_id_usuario($usuario->getId()));
        }

        $datos["usuarios"] = $resultado["usuarios"];
        $cantPaginasRestantes = (ceil( $resultado["total_usuarios"] / $limite)) - $pagina;
        $view->cargarPagina($datos,$cantPaginasRestantes);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function bloquearUsuario(){
    try {
      $repoUsuario = RepositorioUsuario::getInstance();
      $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

      $id = $_POST["id"];

      if ($id == $_SESSION["id"]) {
        TwigView::jsonEncode(array('estado' => "auto_bloqueo"));
      }elseif ($repoUsuarioTieneRol->usuario_es_admin($id) && ($repoUsuarioTieneRol->usuarios_activos_admin() == 1)) {
        TwigView::jsonEncode(array('estado' => "un_solo_admin"));
      }else {
        if ($repoUsuario->bloquear_activar($id,0)) {
          TwigView::jsonEncode(array('estado' => "bloqueado"));
        }else {
          TwigView::jsonEncode(array('estado' => "error"));
        }
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function activarUsuario(){
    try {
      $repoUsuario = RepositorioUsuario::getInstance();

      $id = $_POST["id"];

      if ($id == $_SESSION["id"]) {
        TwigView::jsonEncode(array('estado' => "auto_activacion"));
      }else {
        if ($repoUsuario->bloquear_activar($id,1)) {
          TwigView::jsonEncode(array('estado' => "activado"));
        }else {
          TwigView::jsonEncode(array('estado' => "error"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function validarCampos($nombre,$apellido,$email){
    if (!($nombre && $apellido && $email)) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete todos los campos"));
      return false;
    }elseif(!preg_match("/^[a-zA-Z ]+$/",$nombre) || !preg_match("/^[a-zA-Z ]+$/",$apellido)){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Nombre y apellido deben contener solo letras"));
      return false;
    }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Email ingresado es incorrecto"));
      return false;
    }

    return true;
  }

  public function agregarUsuario(){


    try {
      $nombre = strtolower($_POST["nombre"]);
      $apellido = strtolower($_POST["apellido"]);
      $nombreDeUsuario = $_POST["nombreDeUsuario"];
      $contrasena = $_POST["contrasena"];
      $email = strtolower($_POST["email"]);

      $repoUsuario = RepositorioUsuario::getInstance();

      //Valido que el email sea correcto y que los campos no esten vacios
      if ($this->validarCampos($nombre,$apellido,$email)) {
        if (!($nombreDeUsuario && $contrasena)) {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Complete todos los campos"));
        }elseif( preg_match("/[A-ZÑ ]/", $nombreDeUsuario)){
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El nombre de usuario no permite mayusculas ni espacios"));
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
        if ($this->validarCampos($nombre,$apellido,$email)) {
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
      $repoRol = RepositorioRol::getInstance();
      $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

      $rol = $repoRol->obtener_por_id($idRol);
      $activo = RepositorioUsuario::getInstance()->obtener_usuario_por_id($id)->getActivo();

      if ($activo && ($rol->getNombre() == "Administrador") && ($repoUsuarioTieneRol->usuarios_activos_admin() == 1)) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Queda un unico administrador, no puedes quitarle el rol"));
      }else {
        if ($repoUsuarioTieneRol->eliminarRelacion($id, $idRol)) {
          TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Rol quitado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No se pudo realizar la operacion vuelva a intentar mas tarde"));
    }
  }

}

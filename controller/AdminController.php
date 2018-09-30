<?php

class AdminController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
        self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function redirectConfiguracion(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $view = new Configuracion();
      $view->show($config->obtener_configuracion());
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function guardarConfiguracion(){
    try {
      $config = RepositorioConfiguracion::getInstance();
      $view = new Configuracion();

      //intentan hacer un envio vacio desde url por ende redirecciono
      if (! (isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["email"]) && isset($_POST["limite"]) && isset($_POST["habilitado"]))) {
        InicioController::getInstance()->mostrarInicio();
      }else {
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $email = $_POST["email"];
        $limite = $_POST["limite"];
        $habilitado = $_POST["habilitado"];

        //Valido que el email sea correcto y que los campos no esten vacios
        //Valido que el select "habilitado" tenga los valores correctos
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $view->show($config->obtener_configuracion(),"Email incorrecto");
        }elseif(!($titulo && $descripcion && $email && $limite && ($habilitado == "0" || $habilitado == "1"))) {
          $view->show($config->obtener_configuracion(),"Los datos ingresados son incorrectos");
        }else {
          //Guardo la configuracion nueva
          $config->setTitulo($titulo);
          $config->setDescripcion($descripcion);
          $config->setEmail($email);
          $config->setLimite($limite);

          if ($habilitado == 1) {
            $config->habilitar();
          }else {
            $config->deshabilitar();
          }

          $view->show($config->obtener_configuracion(), null, "Los datos se guardaron correctamente");
        }
      }
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function redirectUsuarios(){
    try {
      $repoUsuario = RepositorioUsuario::getInstance();
      $repoRol = RepositorioRol::getInstance();
      $view = new Usuarios();

      $limite = RepositorioConfiguracion::getInstance()->getLimite();
      $usuarios = $repoUsuario->obtener_todos_limite_pagina($limite,1);

      foreach ($usuarios as $usuario) {
        $usuario->setRoles($repoRol->obtener_por_id_usuario($usuario->getId()));
      }

      $view->show($usuarios);
    } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
    }
  }

  public function cargarPagina(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
        $config = RepositorioConfiguracion::getInstance();
        $repoUsuario = RepositorioUsuario::getInstance();
        $view = new Usuarios();

        $pagina = $_POST["pagina"];
        $username = strtolower($_POST["username"]);
        $estado = $_POST["estado"];
        $limite = $config->getLimite();

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
          $view->cargarPagina($usuarios);
        }
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function bloquearUsuario(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else{
        $repoUsuario = RepositorioUsuario::getInstance();

        $id = $_POST["id"];

        if ($repoUsuario->bloquear_usuario($id)) {
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
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
        $repoUsuario = RepositorioUsuario::getInstance();

        $id = $_POST["id"];

        if ($repoUsuario->activar_usuario($id)) {
          TwigView::jsonEncode(array('estado' => "activado"));
        }else {
          TwigView::jsonEncode(array('estado' => "error"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function agregarUsuario(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else{
        $nombre = strtolower($_POST["nombre"]);
        $apellido = strtolower($_POST["apellido"]);
        $nombreDeUsuario = strtolower($_POST["nombreDeUsuario"]);
        $contrasena = $_POST["contrasena"];
        $email = strtolower($_POST["email"]);

        $repoUsuario = RepositorioUsuario::getInstance();

        //Valido que el email sea correcto y que los campos no esten vacios
        if (!($nombre && $apellido && $nombreDeUsuario && $contrasena && $email)) {
          TwigView::jsonEncode(array('estado' => "datos incorrectos"));
        }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
          TwigView::jsonEncode(array('estado' => "email incorrecto"));
        }elseif ($repoUsuario->username_existe($nombreDeUsuario)) {
          //Valido que no exista el nombre de usuario
          TwigView::jsonEncode(array('estado' => "nombre de usuario existe"));
        }elseif (strlen($contrasena) < 8) {
          TwigView::jsonEncode(array('estado' => "contraseña menor a 8"));
        }else{
          $contrasena = password_hash($contrasena,PASSWORD_DEFAULT);
          $usuario = new Usuario("",$email,$nombreDeUsuario,$contrasena,"","","",$nombre,$apellido,"");
          if ($repoUsuario->insertar_usuario($usuario)){
            TwigView::jsonEncode(array('estado' => "guardado correcto"));
          }else {
            TwigView::jsonEncode(array('estado' => "error"));
          }
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function formularioModificacionUsuario(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
        $id = $_POST["id"];

        $repoUsuario = RepositorioUsuario::getInstance();
        $view = new Usuarios();

        $usuario = $repoUsuario->obtener_usuario_por_id($id);

        $view->formularioModificacionUsuario($usuario);
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function modificarUsuario(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $contrasena = $_POST["contrasena"];
        $email = $_POST["email"];

        $repoUsuario = RepositorioUsuario::getInstance();

        //Valido campos
        if (!($nombre && $apellido && $email)) {
          TwigView::jsonEncode(array('estado' => "datos incorrectos"));
        }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
          TwigView::jsonEncode(array('estado' => "email incorrecto"));
        }else{
          if ($contrasena){
            if (strlen($contrasena) < 8) {
              TwigView::jsonEncode(array('estado' => "contraseña menor a 8"));
            }else {
              //actualizar contraseña
              $repoUsuario->actualizar_informacion_usuario($id,$email,$nombre,$apellido);
              $repoUsuario->actualizar_password_usuario($id, $contrasena);
              TwigView::jsonEncode(array('estado' => "modificado correcto"));
            }
          }else{
            $repoUsuario->actualizar_informacion_usuario($id,$email,$nombre,$apellido);
            TwigView::jsonEncode(array('estado' => "modificado correcto"));
          }
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function cuerpoPanelAdministracionRoles(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
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
          TwigView::jsonEncode(array('estado' => "error"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function agregarRol(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
        $id = $_POST["id"];
        $idRol = $_POST["idRol"];

        $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

        if ($idRol == "no seleccionado") {
          TwigView::jsonEncode(array('estado' => "no seleccionado"));
        }elseif ($repoUsuarioTieneRol->relacion_existe($id,$idRol,0)) {
          TwigView::jsonEncode(array('estado' => "ya tiene este rol"));
        }elseif ($repoUsuarioTieneRol->crearRelacion($id,$idRol)) {
          TwigView::jsonEncode(array('estado' => "agregado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function quitarRol(){
    try {
      if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
        //No es una peticion ajax
        InicioController::getInstance()->mostrarInicio();
      }else {
        $id = $_POST["id"];
        $idRol = $_POST["idRol"];

        $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

        if ($repoUsuarioTieneRol->eliminarRelacion($id, $idRol)) {
          TwigView::jsonEncode(array('estado' => "quitado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }
}

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
    } catch (\Exception $e) {
      $view->jsonEncode(array('estado' => "no hay"));
    }
  }

  public function eliminarUsuario(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $repoUsuario = RepositorioUsuario::getInstance();

    $id = $_POST["id"];

    if ($repoUsuario->eliminar_usuario($id)) {
      echo "eliminado";
    }else {
      echo "no se pudo eliminar";
    }
  }

  public function bloquearUsuario(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $repoUsuario = RepositorioUsuario::getInstance();

    $id = $_POST["id"];

    if ($repoUsuario->bloquear_usuario($id)) {
      echo "bloqueado";
    }else {
      echo "no se pudo bloquear";
    }
  }

  public function activarUsuario(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $repoUsuario = RepositorioUsuario::getInstance();

    $id = $_POST["id"];

    if ($repoUsuario->activar_usuario($id)) {
      echo "activado";
    }else {
      echo "no se pudo activar";
    }
  }

  public function agregarUsuario(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $nombre = strtolower($_POST["nombre"]);
    $apellido = strtolower($_POST["apellido"]);
    $nombreDeUsuario = strtolower($_POST["nombreDeUsuario"]);
    $contrasena = $_POST["contrasena"];
    $email = strtolower($_POST["email"]);

    $repoUsuario = RepositorioUsuario::getInstance();

    //Valido que el email sea correcto y que los campos no esten vacios
    if (!($nombre && $apellido && $nombreDeUsuario && $contrasena && $email)) {
      echo "datos incorrectos";
      return;
    }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
      echo "email incorrecto";
      return;
    }

    //Valido que no exista el nombre de usuario
    if ($repoUsuario->username_existe($nombreDeUsuario)) {
      echo "nombre de usuario existe";
      return;
    }

    $usuario = new Usuario("",$email,$nombreDeUsuario,$contrasena,"","","",$nombre,$apellido,"");
    if ($repoUsuario->insertar_usuario($usuario)){
      echo "guardado correcto";
    }else {
      echo "no se guardo";
    }

  }

  public function formularioModificacionUsuario(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $id = $_POST["id"];

    $repoUsuario = RepositorioUsuario::getInstance();
    $view = new Usuarios();

    $usuario = $repoUsuario->obtener_usuario_por_id($id);

    $view->formularioModificacionUsuario($usuario);
  }

  public function modificarUsuario(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $contrasena = $_POST["contrasena"];
    $email = $_POST["email"];

    $repoUsuario = RepositorioUsuario::getInstance();

    //Valido campos
    if (!($nombre && $apellido && $email && $contrasena)) {
      echo "datos incorrectos";
      return;
    }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
      echo "email incorrecto";
      return;
    }

    if ($repoUsuario->actualizar_informacion_usuario($id,$email,$contrasena,$nombre,$apellido)){
      echo "modificado correcto";
    }else {
      echo "no se modifico";
    }
  }

  public function cuerpoPanelAdministracionRoles(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

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
      echo "error";
    }
  }

  public function agregarRol(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $id = $_POST["id"];
    $idRol = $_POST["idRol"];

    $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

    if ($idRol == "no seleccionado") {
      echo "no seleccionado";
      exit;
    }

    if ($repoUsuarioTieneRol->relacion_existe($id,$idRol,0)) {
      echo "ya tiene este rol";
      exit;
    }

    if ($repoUsuarioTieneRol->crearRelacion($id,$idRol)) {
      echo "agregado correcto";
    }else {
      echo "error";
    }

  }

  public function quitarRol(){
    $id = $_POST["id"];
    $idRol = $_POST["idRol"];

    $repoUsuarioTieneRol = RepositorioUsuarioTieneRol::getInstance();

    if ($repoUsuarioTieneRol->eliminarRelacion($id, $idRol)) {
      echo "quitado correcto";
    }else {
      echo "error";
    }
  }

}

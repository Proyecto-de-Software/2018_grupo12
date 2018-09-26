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
    $config = RepositorioConfiguracion::getInstance();
    $view = new Configuracion();
    $view->show($config->obtener_configuracion());
  }

  public function guardarConfiguracion(){
    $config = RepositorioConfiguracion::getInstance();
    $view = new Configuracion();

    //intentan hacer un envio vacio desde url por ende redirecciono
    if (! (isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["email"]) && isset($_POST["limite"]) && isset($_POST["habilitado"]))) {
      header("Location: ?action=configuracion");
      return;
    }

    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $email = $_POST["email"];
    $limite = $_POST["limite"];
    $habilitado = $_POST["habilitado"];

    //Valido que el email sea correcto y que los campos no esten vacios
    //Valido que el select "habilitado" tenga los valores correctos
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $view->show($config->obtener_configuracion(),"Email incorrecto");
      return;
    }elseif(!($titulo && $descripcion && $email && $limite && ($habilitado == "0" || $habilitado == "1"))) {
      $view->show($config->obtener_configuracion(),"Los datos ingresados son incorrectos");
      return;
    }

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

  public function redirectUsuarios(){
    $repoUsuarios = RepositorioUsuario::getInstance();
    $view = new Usuarios();

    $limite = RepositorioConfiguracion::getInstance()->getLimite();
    $usuarios = $repoUsuarios->obtener_todos_limite_pagina($limite,1);

    $view->show($usuarios);
  }

  public function cargarPagina(){
    if(! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    {
      //No es una peticion ajax
      header("Location: index.php");
    	exit;
    }

    $config = RepositorioConfiguracion::getInstance();
    $repoUsuario = RepositorioUsuario::getInstance();
    $view = new Usuarios();

    $pagina = $_POST["pagina"];
    $username = strtolower($_POST["username"]);
    $estado = $_POST["estado"];
    $limite = $config->getLimite();

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
      echo "no hay";
    }else {
      $view->cargarPagina($usuarios);
    }
  }

}

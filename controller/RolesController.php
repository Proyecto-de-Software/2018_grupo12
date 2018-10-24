<?php

class RolesController {

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
        self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {

  }

  public function redirectRoles(){
    try {
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Roles();

      $id = $_SESSION["id"];

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"rol");
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
      $repoRol = RepositorioRol::getInstance();
      $repoPermisos = RepositorioPermiso::getInstance();
      $view = new Roles();

      $pagina = $_POST["pagina"];
      $nombre = strtolower($_POST["nombre"]);
      $limite = $config->getLimite();
      $id = $_SESSION["id"];

      if ($nombre) {
        $roles = $repoRol->obtener_roles_por_nombre($nombre, $limite, $pagina);
      }else {
        $roles = $repoRol->obtener_todos_los_roles_pagina($limite, $pagina);
      }

      if (empty($roles)) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{

        $datos["roles"] = $roles;
        $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"paciente");

        $view->cargarPagina($datos);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }
}

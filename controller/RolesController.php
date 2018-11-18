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

      $datos["csrf_token"] = Validador::getInstance()->get_token();
      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"rol");
      $datos["todos_los_permisos"] = $repoPermisos->permisos_todos();
      $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();

      $view->show($datos);
    } catch (\Exception $e) {
      $datos["csrf_token"] = Validador::getInstance()->get_token();
      $view = new PaginaError();
      $view->show($datos);
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
        $resultado = $repoRol->obtener_roles_por_nombre($nombre, $limite, $pagina);
      }else {
        $resultado = $repoRol->obtener_todos_los_roles_pagina($limite, $pagina);
      }
      $roles = $resultado["roles"];

      if (empty($roles)) {
        $view->jsonEncode(array('estado' => "no hay"));
      }else{

        $datos["roles"] = $roles;
        $datos["permisos"] = $repoPermisos->permisos_id_usuario_modulo($id,"rol");
        $cantPaginasRestantes = (ceil( $resultado["total_roles"] / $limite)) - $pagina;

        $view->cargarPagina($datos, $cantPaginasRestantes);
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error"));
    }
  }

  public function agregarRol(){
    try {
      $repoRol = RepositorioRol::getInstance();

      $nombre = $_POST["nombre"];

      if (! $nombre) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Ingrese el nombre del rol"));
      }elseif (preg_match("/[A-ZÑ ]/", $nombre)) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El nombre de rol no permite mayusculas ni espacios"));
      }elseif ($repoRol->rol_existe($nombre)){
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El nombre de rol ya existe"));
      }else {
        if ($repoRol->insertar_rol($nombre)) {
          TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Rol guardado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
        }
      }
    }catch (\Exception $e){
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }

  public function informacionRol(){
    try {
      $repoRolTienePermiso = RepositorioRolTienePermiso::getInstance();
      $id = $_POST["id"];

      if ($id) {
        $rol = $repoRolTienePermiso->info_rol($id);
        TwigView::jsonEncode(array('estado' => "success", 'rol' => $rol));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Peticion vacia"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }

  }

  public function quitarPermisoAlRol(){
    try {
      $repoRolTienePermiso = RepositorioRolTienePermiso::getInstance();
      $idRol = $_POST["idRol"];
      $idPermiso = $_POST["idPermiso"];

      if ($idRol && $idPermiso) {
        //$rol = $repoRolTienePermiso->info_rol($id);
        //TwigView::jsonEncode(array('estado' => "success", 'rol' => $rol));
      }else {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Peticion vacia"));
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }

  public function modificarNombreRol(){
    try {
      $repoRol = RepositorioRol::getInstance();
      $id = $_POST["id"];
      $nombre = $_POST["nombre"];

      if (! $nombre) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Ingrese el nombre del rol"));
      }elseif (preg_match("/[A-ZÑ ]/", $nombre)) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "El nombre de rol no permite mayusculas ni espacios"));
      }else {
        if ($repoRol->actualizar_rol($id,$nombre)) {
          TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Rol actualizado correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion vuelva a intentar mas tarde"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }

  public function agregarPermisos(){
    try {
      $repoRolTienePermiso = RepositorioRolTienePermiso::getInstance();
      $idRol = $_POST["idRol"];
      $idPermisos = (isset($_POST["idPermisos"])) ? $_POST["idPermisos"] : "";

      if (! $idPermisos) {
        TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "Seleccione al menos un rol"));
      }else {
        if ($repoRolTienePermiso->agregar_permisos($idRol, $idPermisos)) {
          TwigView::jsonEncode(array('estado' => "success", 'mensaje' => "Roles agregados correctamente"));
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
        }
      }
    } catch (\Exception $e) {
      TwigView::jsonEncode(array('estado' => "error", 'mensaje'=> "No se pudo realizar la operacion, vuelva a intentar mas tarde"));
    }
  }
}

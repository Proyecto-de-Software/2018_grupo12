<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once './vendor/autoload.php';

//Agregar control de mantenimiento
//Agregar control de sesiones
if(isset($_GET["action"])){
  switch ($_GET["action"]) {
    case 'login':
      LoginController::getInstance()->mostrarLogin();
      break;
      case 'admin':
        LoginController::getInstance()->redirectAdmin();
        break;
      case 'configuracion':
        AdminController::getInstance()->redirectConfiguracion();
        break;
      case 'guardarConfiguracion':
        AdminController::getInstance()->guardarConfiguracion();
        break;
      case 'usuarios':
        AdminController::getInstance()->redirectUsuarios();
        break;
      case 'cargarPagina':
        AdminController::getInstance()->cargarPagina();
        break;
      case 'bloquearUsuario':
        AdminController::getInstance()->bloquearUsuario();
        break;
      case 'activarUsuario':
        AdminController::getInstance()->activarUsuario();
        break;
      case 'agregarUsuario':
        AdminController::getInstance()->agregarUsuario();
        break;
      case 'formularioModificacionUsuario':
        AdminController::getInstance()->formularioModificacionUsuario();
        break;
      case 'modificarUsuario':
        AdminController::getInstance()->modificarUsuario();
        break;
      case 'cuerpoPanelAdministracionRoles':
        AdminController::getInstance()->cuerpoPanelAdministracionRoles();
        break;
      case 'agregarRol':
        AdminController::getInstance()->agregarRol();
        break;
      case 'quitarRol':
        AdminController::getInstance()->quitarRol();
        break;
    default:
      InicioController::getInstance()->mostrarInicio();
  }
}else{
    InicioController::getInstance()->mostrarInicio();
}

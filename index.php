<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once './controller/autoCargador.php';

//Agregar control de mantenimiento
//Agregar control de sesiones
//Agregar control de permisos
if(isset($_GET["action"])){
  switch ($_GET["action"]) {
    case 'login':
      LoginController::getInstance()->mostrarLogin();
      break;
    case 'home':
      LoginController::getInstance()->redirectHome();
      break;
    case 'configuracion':
      ConfiguracionController::getInstance()->redirectConfiguracion();
      break;
    case 'guardarConfiguracion':
      ConfiguracionController::getInstance()->guardarConfiguracion();
      break;
    case 'usuarios':
      UsuariosController::getInstance()->redirectUsuarios();
      break;
    case 'cargarPaginaUsuarios':
      UsuariosController::getInstance()->cargarPagina();
      break;
    case 'bloquearUsuario':
      UsuariosController::getInstance()->bloquearUsuario();
      break;
    case 'activarUsuario':
      UsuariosController::getInstance()->activarUsuario();
      break;
    case 'agregarUsuario':
      UsuariosController::getInstance()->agregarUsuario();
      break;
    case 'formularioModificacionUsuario':
      UsuariosController::getInstance()->formularioModificacionUsuario();
      break;
    case 'modificarUsuario':
      UsuariosController::getInstance()->modificarUsuario();
      break;
    case 'cuerpoPanelAdministracionRoles':
      UsuariosController::getInstance()->cuerpoPanelAdministracionRoles();
      break;
    case 'agregarRol':
      UsuariosController::getInstance()->agregarRol();
      break;
    case 'quitarRol':
      UsuariosController::getInstance()->quitarRol();
      break;
    case 'pacientes':
      PacientesController::getInstance()->redirectPacientes();
      break;
    case 'cargarPaginaPacientes':
      PacientesController::getInstance()->cargarPagina();
      break;
    default:
      InicioController::getInstance()->mostrarInicio();
  }
}else{
    InicioController::getInstance()->mostrarInicio();
}

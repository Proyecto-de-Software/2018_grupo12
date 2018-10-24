<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

session_start();

require_once './controller/autoCargador.php';
try{
if(isset($_GET["action"])){
  $validador = Validador::getInstance();
  $isAjax = $validador->isAjax();
  switch ($_GET["action"]) {
    case 'login':
      if ($validador->sesion_iniciada()) {
        LoginController::getInstance()->redirectHome();
      }else {
        LoginController::getInstance()->mostrarLogin();
      }
      break;
    case 'validar':
      if (! $isAjax) {
        InicioController::getInstance()->mostrarInicio();
      }elseif($validador->sesion_iniciada()) {
        TwigView::jsonEncode(array('estado' => "sesion ya iniciada"));
      }else {
        UsuariosController::getInstance()->loguear();
      }
      break;
    case 'cerrarSesion':
      UsuariosController::getInstance()->logout();
      break;
    case 'home':
      InicioController::getInstance()->mostrarInicio();
      break;
    case 'configuracion':
      if ($validador->sesion_modulo("configuracion")){
        ConfiguracionController::getInstance()->redirectConfiguracion();
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'guardarConfiguracion':
      if ($isAjax) {
        if ($validador->sesion_permiso("configuracion_update")) {
          ConfiguracionController::getInstance()->guardarConfiguracion();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'usuarios':
      if ($validador->sesion_modulo("usuario")){
        UsuariosController::getInstance()->redirectUsuarios();
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'cargarPaginaUsuarios':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_index")) {
          UsuariosController::getInstance()->cargarPagina();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'bloquearUsuario':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_activarBloquear")) {
          UsuariosController::getInstance()->bloquearUsuario();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'activarUsuario':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_activarBloquear")) {
          UsuariosController::getInstance()->activarUsuario();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'agregarUsuario':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_new")) {
          UsuariosController::getInstance()->agregarUsuario();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'formularioModificacionUsuario':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_update")) {
          UsuariosController::getInstance()->formularioModificacionUsuario();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'modificarUsuario':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_update")) {
          UsuariosController::getInstance()->modificarUsuario();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'cuerpoPanelAdministracionRoles':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_administrarRoles")) {
          UsuariosController::getInstance()->cuerpoPanelAdministracionRoles();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'agregarRol':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_administrarRoles")) {
          UsuariosController::getInstance()->agregarRol();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'quitarRol':
      if ($isAjax) {
        if ($validador->sesion_permiso("usuario_administrarRoles")) {
          UsuariosController::getInstance()->quitarRol();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'pacientes':
      if ($validador->sesion_modulo("paciente")){
        PacientesController::getInstance()->redirectPacientes();
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'cargarPaginaPacientes':
      if ($isAjax) {
        if ($validador->sesion_permiso("paciente_index")) {
          PacientesController::getInstance()->cargarPagina();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'eliminarPaciente':
      if ($isAjax) {
        if ($validador->sesion_permiso("paciente_destroy")) {
          PacientesController::getInstance()->eliminarPaciente();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'agregarPacienteSimple':
      if ($isAjax) {
        if ($validador->sesion_permiso("paciente_new")) {
          PacientesController::getInstance()->agregarPacienteSimple();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'agregarPacienteCompleto':
      if ($isAjax) {
        if ($validador->sesion_permiso("paciente_new")) {
          PacientesController::getInstance()->agregarPacienteCompleto();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'detallePaciente':
      if ($isAjax) {
        if ($validador->sesion_permiso("paciente_show")) {
          PacientesController::getInstance()->detallePaciente();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'formularioModificacionPaciente':
      if ($isAjax) {
        if ($validador->sesion_permiso("paciente_update")) {
          PacientesController::getInstance()->formularioModificacionPaciente();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'modificarPaciente':
      if ($isAjax) {
        if ($validador->sesion_permiso("paciente_update")) {
          PacientesController::getInstance()->modificarPaciente();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'roles':
      if ($validador->sesion_modulo("rol")){
        RolController::getInstance()->redirectRoles();
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    case 'cargarPaginaRoles':
      if ($isAjax) {
        if ($validador->sesion_permiso("rol_index")) {
          RolesController::getInstance()->cargarPagina();
        }else {
          TwigView::jsonEncode(array('estado' => "error", 'mensaje' => "No posee permisos para realizar la acción"));
        }
      }else {
        InicioController::getInstance()->mostrarInicio();
      }
      break;
    default:
      InicioController::getInstance()->mostrarInicio();
  }
}else{
  InicioController::getInstance()->mostrarInicio();
}
} catch (\Exception $e) {
  $view = new PaginaError();
  $view->show();
}

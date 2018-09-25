<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once './vendor/autoload.php';


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
    default:
      InicioController::getInstance()->mostrarInicio();
  }
}else{
    InicioController::getInstance()->mostrarInicio();
}

<?php
require "./model/repositorioUsuario.php";

class UserController {

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
  
    if(!(isset($_POST['usuario'])&&isset($_POST['contrasena']))){
        //fallaron las variables
      }else{
        if($repoUsuario->username_existe($_POST['usuario'])){
           $usuario = $repoUsuario->obtener_usuario_por_username($_POST['usuario']);
           //1 activo SI   .... 0 Bloqueado
           //if (password_verify($_POST['contrasena'], $usuario->getPassword()) ) {
           if($usuario->getPassword() == $_POST['contrasena'] && $usuario->getActivo() ){ 
              session_start(); 
              $_SESSION['userName']=$usuario->getUsername();
              $_SESSION['id']=$usuario->getId();
              echo "logueado";
              //falta vista
           }else{
            echo "no logueado Bloqueado";
            //falta vista
           }

        }else{
          //falta vista
          echo "usuario no existe";
        }
      }
  } catch (\Exception $e) {
      $view = new PaginaError();
      $view->show();
  }

  }

  public function logout() {
    session_destroy();  
    header("location: index.php");
  }

  public function cargarHeader(){


  }

}

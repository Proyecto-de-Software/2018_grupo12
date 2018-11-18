
<?php

class Validador{
  private static $instance;

  public static function getInstance(){
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function sesion_iniciada(){
    if(isset($_SESSION['id'])){
      return true;
    }else{
      return false;
    }
  }
  public function sesion_permiso($permiso){
    if($this -> sesion_iniciada()){
      return RepositorioPermiso::getInstance()->id_usuario_tiene_permiso($_SESSION['id'],$permiso);
    }else{
      return false;
    }
  }
  public function pagina_habilitada(){
    if(RepositorioConfiguracion::getInstance()->getHabilitado()){
      return true;
    }else{
      return false;
    }
  }

  public function sesion_modulo($modulo){
    if($this -> sesion_iniciada()){
      $permisos = RepositorioPermiso::getInstance()->permisos_id_usuario_modulo($_SESSION['id'],$modulo);
      return count($permisos) != 0;
    }else{
      return false;
    }
  }

  public function isAjax(){
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  }

  public function mostrarPaginaMantenimiento(){
    $view = new PaginaMantenimiento();

    $datos["csrf_token"] = Validador::getInstance()->get_token();
    $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();
    $datos["descripcion"] = RepositorioConfiguracion::getInstance()->getDescripcion();

    if (Validador::getInstance()->sesion_iniciada()) {
      $repoPermisos = RepositorioPermiso::getInstance();
      $id = $_SESSION["id"];

      $datos["modulos"] = $repoPermisos->modulos_id_usuario_admin($id,0);
      $datos["modulosAdministracion"] = $repoPermisos->modulos_id_usuario_admin($id,1);
      $datos["username"] = $_SESSION["userName"];
      $datos["pagina"] = 'home.twig';
    }else {
      $datos["pagina"] = 'inicio.twig';
    }
    $view->show($datos);
  }

  public function get_token() {
    if(isset($_SESSION['token'])) {
      return $_SESSION['token'];
    } else {
      $token = bin2hex(random_bytes(32));
      $_SESSION['token'] = $token;
      return $token;
    }
  }

  public function check_valid_post() {
    if(isset($_POST['token']) && ($_POST['token'] == $this->get_token())) {
      return true;
    } else {
      return false;
    }
  }

  public function sanitizePostAndGet(){
    foreach ($_POST as $clave => $valor){
      $_POST[$clave] = filter_var($valor, FILTER_SANITIZE_STRING);
    }

    foreach ($_GET as $clave => $valor){
      $_GET[$clave] = filter_var($valor, FILTER_SANITIZE_STRING);
    }
  }
}

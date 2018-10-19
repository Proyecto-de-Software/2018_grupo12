
<?php

class Validador{
    private static $instance;

    public static function getInstance()
    {
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
      $datos["tituloPag"] = RepositorioConfiguracion::getInstance()->getTitulo();

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
}

<?php

class Usuarios extends TwigView {

  public function show($datos) {
    echo self::getTwig()->render('usuarios.twig',$datos);
  }

  public function cargarPagina($datos,$cantPaginasRestantes){
    $contenido = self::getTwig()->render('cuerpoTablaUsuarios.twig',$datos);
    $respuesta = array('estado' => "si hay",'pagRestantes' => $cantPaginasRestantes,'contenido' => $contenido);

    self::jsonEncode($respuesta);
  }

  public function formularioModificacionUsuario($usuario){
    $contenido = self::getTwig()->render('formularioModificacionUsuario.twig',array('usuario' => $usuario ));
    $datos = array('estado' => "success",'contenido' => $contenido);

    self::jsonEncode($datos);
  }

  public function cuerpoPanelAdministracionRoles($usuario, $roles){
    $contenido = self::getTwig()->render('cuerpoPanelAdministracionRoles.twig',array('usuario' => $usuario, 'roles' => $roles ));
    $datos = array('estado' => "success",'contenido' => $contenido);

    self::jsonEncode($datos);
  }

}

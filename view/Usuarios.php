<?php

class Usuarios extends TwigView {

  public function show($usuarios) {

    echo self::getTwig()->render('usuarios.twig',array('usuarios' => $usuarios ));

  }

  public function cargarPagina($usuarios){
    $contenido = self::getTwig()->render('cuerpoTablaUsuarios.twig',array('usuarios' => $usuarios ));
    $datos = array('estado' => "si hay",'contenido' => $contenido);

    self::jsonEncode($datos);
  }

  public function formularioModificacionUsuario($usuario){
    echo self::getTwig()->render('formularioModificacionUsuario.twig',array('usuario' => $usuario ));
  }

}

<?php

class Usuarios extends TwigView {

  public function show($usuarios) {

    echo self::getTwig()->render('usuarios.twig',array('usuarios' => $usuarios ));

  }

  public function cargarPagina($usuarios){
    echo self::getTwig()->render('cuerpoTablaUsuarios.twig',array('usuarios' => $usuarios ));
  }

  public function formularioModificacionUsuario($usuario){
    echo self::getTwig()->render('formularioModificacionUsuario.twig',array('usuario' => $usuario ));
  }

}
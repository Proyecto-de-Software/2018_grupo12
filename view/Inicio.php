<?php

class Inicio extends TwigView {

    public function show($datos) {

        echo self::getTwig()->render('inicio.twig', $datos);


    }

}

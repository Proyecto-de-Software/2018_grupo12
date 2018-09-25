<?php

class Inicio extends TwigView {

    public function show() {

        echo self::getTwig()->render('inicio.twig');


    }

}

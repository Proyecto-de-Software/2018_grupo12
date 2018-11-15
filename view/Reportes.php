<?php

class Reportes extends TwigView {

    public function show($datos) {

        echo self::getTwig()->render('reportes.twig',$datos);


    }

}

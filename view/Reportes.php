<?php

class Reportes extends TwigView {

    public function show() {

        echo self::getTwig()->render('reportes.twig');


    }

}

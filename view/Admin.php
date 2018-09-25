<?php

class Admin extends TwigView {

    public function show() {

        echo self::getTwig()->render('admin.twig');


    }

}

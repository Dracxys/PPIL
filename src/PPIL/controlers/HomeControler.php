<?php
namespace  PPIL\controlers;

use PPIL\views\AbstractView as AbstractView;

class HomeControler{

    public function accueil() {
        $v = new AbstractView();
        echo $v->headHTML();
    }

}
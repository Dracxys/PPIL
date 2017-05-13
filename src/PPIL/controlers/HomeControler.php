<?php
namespace  PPIL\controlers;

use PPIL\views\AbstractView as AbstractView;
use PPIL\views\VueHome;

class HomeControler{

    public function accueil() {
        $v  = new VueHome();
        echo $v->home();
    }

    public function connection() {
        #TODO
    }


}
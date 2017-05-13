<?php
namespace  PPIL\controlers;

use PPIL\views\AbstractView as AbstractView;
use PPIL\views\VueHome;
use PPIL\views\VueUtilisateur;

class HomeControler{

    public function accueil() {
        $v  = new VueHome();
        echo $v->home();
    }

    public function connection() {
        #TODO



        #si tout se passe bien
        $v  = new VueUtilisateur();
        echo $v->home();
    }


}
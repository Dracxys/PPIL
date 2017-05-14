<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 13/05/2017
 * Time: 22:00
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\views\VueUtilisateur;

class UtilisateurControler
{

    public function home(){
        $v = new VueUtilisateur();
        if(isset($_SESSION['mail'])){
            echo $v->home();
        }
    }

    public function journal(){
        $v = new VueUtilisateur();
        echo $v->journal();
    }
}
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
            $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
            echo $v->home($e);
        }
    }
}